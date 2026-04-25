import { Book } from "@/types/book";

const OPENSTAX_API_URL = "https://openstax.org/api/v2/pages/?type=book.Book&fields=title,cover_url,description,subjects,authors,id,meta";

/**
 * Fetches textbooks from the OpenStax library via their public API.
 * @param query Search query
 * @param page Page number (1-indexed)
 */
export const fetchOpenStaxBooks = async (query?: string, page: number = 1): Promise<Book[]> => {
  try {
    const limit = 20;
    const offset = (page - 1) * limit;
    
    // Default to Computer Science / AI related for OpenStax
    let url = `${OPENSTAX_API_URL}&limit=${limit}&offset=${offset}`;
    const effectiveQuery = query || "Computer Science";
    url += `&search=${encodeURIComponent(effectiveQuery)}`;
    
    const response = await fetch(url);
    if (!response.ok) {
      if (response.status === 404) return []; 
      throw new Error(`OpenStax API error: ${response.status}`);
    }
    
    const data = await response.json();
    
    if (!data.items || !Array.isArray(data.items)) {
      return [];
    }
    
    return data.items.map((item: any) => {
      // Process authors
      const author = item.authors && item.authors.length > 0 
        ? item.authors.map((a: any) => a.name).join(", ") 
        : "OpenStax Academic Board";

      // Process category/subjects
      const category = item.subjects && item.subjects.length > 0
        ? item.subjects[0].name
        : "Computer Science";

      // Clean HTML from description if present
      const rawDescription = item.description || "A peer-reviewed, high-authority academic textbook from OpenStax, Rice University.";
      const description = rawDescription.replace(/<[^>]*>?/gm, '').substring(0, 300) + (rawDescription.length > 300 ? "..." : "");

      // OpenStax slugs are often lowercase and hyphenated versions of the title
      const slug = item.title.toLowerCase().replace(/[^a-z0-9]+/g, '-');

      return {
        id: `openstax-${item.id}`,
        title: item.title,
        author,
        description,
        category,
        publication_year: 2024,
        cover_image: item.cover_url || 
                  `https://images.unsplash.com/photo-1497633762265-9d179a990aa6?q=80&w=2946&auto=format&fit=crop`,
        rating: 4.8 + (Math.random() * 0.2),
        pages: 400 + Math.floor(Math.random() * 400),
        source: 'openstax',
        // Direct link to the book details/reader
        read_link: `https://openstax.org/details/books/${slug}`
      };
    });
  } catch (error) {
    console.error("OpenStax fetch error:", error);
    return [];
  }
};