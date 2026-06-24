import "jsr:@supabase/functions-js/edge-runtime.d.ts";
import { createClient } from 'https://esm.sh/@supabase/supabase-js@2';

const corsHeaders = {
  'Access-Control-Allow-Origin': '*',
  'Access-Control-Allow-Headers': 'authorization, x-client-info, apikey, content-type',
};

Deno.serve(async (req: Request) => {
  // Handle CORS
  if (req.method === 'OPTIONS') {
    return new Response('ok', { headers: corsHeaders });
  }

  try {
    const supabaseClient = createClient(
      Deno.env.get('SUPABASE_URL') ?? '',
      Deno.env.get('SUPABASE_SERVICE_ROLE_KEY') ?? ''
    );

    // Get the current user
    const authHeader = req.headers.get('Authorization');
    const token = authHeader?.replace('Bearer ', '');
    const { data: { user }, error: authError } = await supabaseClient.auth.getUser(token);

    if (authError || !user) {
      throw new Error('Unauthorized');
    }

    // Get user's institution
    const { data: profile, error: profileError } = await supabaseClient
      .from('profiles')
      .select('institution_id')
      .eq('id', user.id)
      .single();

    if (profileError || !profile?.institution_id) {
      // Fallback for demo or users without institution
      // We can use a default demo DSpace if needed
    }

    const institutionId = profile?.institution_id;

    // Get DSpace config
    const { data: dspaceConfig, error: configError } = await supabaseClient
      .from('institution_dspace_configs')
      .select('*')
      .eq('institution_id', institutionId)
      .eq('is_active', true)
      .single();

    // Default demo DSpace if no config found
    const DSPACE_API_BASE = dspaceConfig?.api_base_url || "https://demo7.dspace.org/server/api";
    const DSPACE_TOKEN = dspaceConfig?.api_token;

    const { action, query, page = 0, size = 20, itemData } = await req.json();

    let result;

    if (action === 'search') {
      const searchUrl = `${DSPACE_API_BASE}/discover/search/objects?query=${encodeURIComponent(query || "*")}&size=${size}&page=${page}`;
      const response = await fetch(searchUrl, {
        headers: DSPACE_TOKEN ? { 'Authorization': `Bearer ${DSPACE_TOKEN}` } : {}
      });
      result = await response.json();
    } else if (action === 'submit' && itemData) {
      // Logic for submitting an item to DSpace
      // Requires authentication usually
      const submitUrl = `${DSPACE_API_BASE}/submission/workspaceitems`;
      const response = await fetch(submitUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          ...(DSPACE_TOKEN ? { 'Authorization': `Bearer ${DSPACE_TOKEN}` } : {})
        },
        body: JSON.stringify(itemData)
      });
      result = await response.json();
    } else {
      throw new Error('Invalid action');
    }

    return new Response(
      JSON.stringify(result),
      { headers: { ...corsHeaders, 'Content-Type': 'application/json' } }
    );
  } catch (error) {
    return new Response(
      JSON.stringify({ error: error.message }),
      { status: 400, headers: { ...corsHeaders, 'Content-Type': 'application/json' } }
    );
  }
});