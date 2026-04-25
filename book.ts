export interface Book {
  id: string;
  title: string;
  author: string;
  description: string;
  category: string;
  publication_year: number;
  cover_image: string;
  rating: number;
  pages: number;
  created_at?: string;
  source?: 'dare-access' | 'gutenberg' | 'dspace' | 'openstax' | 'openlibrary' | 'doab' | 'internetarchive' | 'loc' | 'doaj' | 'springer' | 'crossref';
  read_link?: string;
  content?: string; 
  summary?: string; 
  publisher?: string;
  source_url?: string;
  discipline?: 'STEM' | 'Humanities' | 'Engineering' | 'Law' | 'Health' | 'Arts' | 'Education 5.0';
  isSaved?: boolean;
}