import { Book } from "@/types/book";
import { dareAccess as supabase } from "./supabase";

/**
 * Fetches items from the institutional DSpace library via the Supabase bridge.
 * This automatically handles institutional routing and credentials.
 */
export const fetchDSpaceBooks = async (query?: string, page: number = 0, size: number = 20): Promise<Book[]> => {
  try {
    const { data, error } = await supabase.functions.invoke('dspace-bridge', {
      body: { 
        action: 'search', 
        query: query || "*", 
        page, 
        size 
      }
    });

    if (error) throw error;
    
    // DSpace 7 response path: _embedded.searchResult._embedded.objects
    const objects = data._embedded?.searchResult?._embedded?.objects || [];
    
    // Get the base URL from the response or use a placeholder
    // In a real scenario, the bridge could return the public URL for item access
    const { data: config } = await supabase.from('institution_dspace_urls').select('api_base_url').single();
    const publicUrlBase = config?.api_base_url?.replace('/server/api', '') || "https://demo7.dspace.org";

    return objects.map((obj: any) => {
      const item = obj._embedded?.indexableObject;
      const metadata = item?.metadata || {};
      
      const getMetadataValue = (key: string) => {
        return metadata[key] && metadata[key].length > 0 ? metadata[key][0].value : null;
      };

      const title = getMetadataValue("dc.title") || "Untitled Work";
      const author = getMetadataValue("dc.contributor.author") || 
                     getMetadataValue("dc.creator") || 
                     "Unknown Author";
      const description = getMetadataValue("dc.description.abstract") || 
                          getMetadataValue("dc.description") || 
                          "Institutional Research Resource.";
      const yearStr = getMetadataValue("dc.date.issued") || "2024";
      const year = parseInt(yearStr.substring(0, 4)) || 2024;
      
      const coverImage = "https://images.unsplash.com/photo-1532012197267-da84d127e765?q=80&w=2787&auto=format&fit=crop";
      const handle = getMetadataValue("dc.identifier.uri") || "";

      return {
        id: `dspace-${item.uuid || Math.random().toString(36).substr(2, 9)}`,
        title,
        author,
        description,
        category: getMetadataValue("dc.subject") || "Institutional Repository",
        publication_year: year,
        cover_image: coverImage,
        rating: 4.8, 
        pages: 35 + Math.floor(Math.random() * 40), 
        source: 'dspace' as const,
        read_link: handle,
        publisher: getMetadataValue("dc.publisher") || "University Library",
        source_url: `${publicUrlBase}/items/${item.uuid}`
      };
    });
  } catch (error) {
    console.error("DSpace fetch error via bridge:", error);
    return [];
  }
};

/**
 * Submits a new item to the institutional DSpace.
 */
export const submitToDSpace = async (itemData: any): Promise<any> => {
    try {
        const { data, error } = await supabase.functions.invoke('dspace-bridge', {
            body: { 
                action: 'submit', 
                itemData 
            }
        });
        if (error) throw error;
        return data;
    } catch (error) {
        console.error("DSpace submission error:", error);
        throw error;
    }
};