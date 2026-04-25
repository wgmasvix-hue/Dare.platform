import { createClient } from '@supabase/supabase-js';
import { Book } from '../types/book';
import { StudyGuide } from './ai';

const dareAccessUrl = 'https://odklvauuiitaoenzhlda.supabase.co';
const dareAccessAnonKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im9ka2x2YXV1aWl0YW9lbnpobGRhIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzI1OTczMzIsImV4cCI6MjA4ODE3MzMzMn0.ZTiLAjhbN867KYVQENh1ZQ7MD91faj3GqY-8FbHl1VY';

export const dareAccess = createClient(dareAccessUrl, dareAccessAnonKey);

/**
 * Fetches all books from the Dare Digital Library.
 */
export const fetchBooks = async (): Promise<Book[]> => {
  const { data, error } = await dareAccess
    .from('digital_library_books')
    .select('*')
    .order('created_at', { ascending: false });

  if (error) {
    console.error('Error fetching books:', error);
    return [];
  }

  return data as Book[];
};

/**
 * Adds a single book to the digital library.
 */
export const addBook = async (book: Omit<Book, 'id'>) => {
  const { data, error } = await dareAccess
    .from('digital_library_books')
    .insert([book])
    .select();

  if (error) {
    console.error('Error adding book:', error);
    throw error;
  }

  return data[0] as Book;
};

/**
 * Fetches a study guide for a specific book.
 */
export const getStudyGuide = async (bookId: string): Promise<StudyGuide | null> => {
  try {
    const { data, error } = await dareAccess
      .from('education_5_study_guides')
      .select('guide_data')
      .eq('book_id', bookId)
      .maybeSingle();

    if (error) {
      if (error.code === 'PGRST116') return null; // Not found
      console.error('Error fetching study guide:', error);
      return null;
    }

    return data?.guide_data as StudyGuide || null;
  } catch (err) {
    console.error('Unexpected error fetching study guide:', err);
    return null;
  }
};

/**
 * Saves or updates a study guide in Supabase.
 */
export const saveStudyGuide = async (bookId: string, guide: StudyGuide) => {
  const { data, error } = await dareAccess
    .from('education_5_study_guides')
    .upsert({ 
      book_id: bookId, 
      guide_data: guide,
      updated_at: new Date().toISOString()
    }, { onConflict: 'book_id' })
    .select();

  if (error) {
    console.error('Error saving study guide:', error);
    throw error;
  }

  // Log the interaction
  await logInteraction(bookId, 'study_guide_generated');

  return data[0];
};

/**
 * Fetches all AI analysis results (summaries) for a specific book.
 */
export const getAIAnalysis = async (bookId: string) => {
  const { data, error } = await dareAccess
    .from('ai_analysis_results')
    .select('*')
    .eq('book_id', bookId)
    .maybeSingle();

  if (error && error.code !== 'PGRST116') {
    console.error('Error fetching AI analysis:', error);
    return null;
  }

  return data;
};

/**
 * Saves AI analysis results in Supabase.
 */
export const saveAIAnalysis = async (bookId: string, analysis: any) => {
  const { data, error } = await dareAccess
    .from('ai_analysis_results')
    .upsert({ 
      book_id: bookId, 
      summary_data: analysis,
      updated_at: new Date().toISOString()
    }, { onConflict: 'book_id' })
    .select();

  if (error) {
    console.error('Error saving AI analysis:', error);
    throw error;
  }

  return data[0];
};

/**
 * Fetches all notes for a specific book and user.
 */
export const fetchBookNotes = async (bookId: string) => {
  const { data, error } = await dareAccess
    .from('book_notes')
    .select('*')
    .eq('book_id', bookId)
    .order('created_at', { ascending: false });

  if (error) {
    console.error('Error fetching notes:', error);
    return [];
  }

  return data;
};

/**
 * Adds a new note to a book.
 */
export const addNote = async (note: {
  book_id: string;
  note_text: string;
  page_number?: number;
  chapter_title?: string;
  is_ai_generated?: boolean;
  note_type?: string;
  source_text_segment?: string;
}) => {
  const { data: userData } = await dareAccess.auth.getUser();
  
  const { data, error } = await dareAccess
    .from('book_notes')
    .insert([{
      ...note,
      user_id: userData.user?.id
    }])
    .select();

  if (error) {
    console.error('Error adding note:', error);
    throw error;
  }

  return data[0];
};

/**
 * Deletes a note from Supabase.
 */
export const deleteNote = async (noteId: string) => {
  const { error } = await dareAccess
    .from('book_notes')
    .delete()
    .eq('id', noteId);

  if (error) {
    console.error('Error deleting note:', error);
    throw error;
  }
};

/**
 * Logs an interaction with a resource in Supabase.
 */
export const logInteraction = async (bookId: string, interactionType: string) => {
  try {
    const { data: userData } = await dareAccess.auth.getUser();
    
    await dareAccess
      .from('resource_interactions')
      .insert({
        book_id: bookId,
        user_id: userData.user?.id,
        interaction_type: interactionType,
        timestamp: new Date().toISOString()
      });
  } catch (err) {
    console.error('Error logging interaction:', err);
  }
};