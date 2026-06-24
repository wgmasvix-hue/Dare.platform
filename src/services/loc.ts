import { Book } from "@/types/book";

const LOC_SEARCH_URL = "https://www.loc.gov/books/";

/**
 * Fetches books from the Library of Congress.
 * @param query Search query
 * @param page Page number
 */
export const fetchLOCBooks = async (query?: string, page: number = 1): Promise<Book[]> => {
  try {
    const searchQuery = query || "literature";
    const url = `${LOC_SEARCH_URL}?q=${encodeURIComponent(searchQuery)}&fo=json&at=results&sp=${page}&c=20`;
    
    const response = await fetch(url);
    if (!response.ok) throw new Error(`Library of Congress API error: ${response.status}`);
    
    const data = await response.json();
    const results = data.results || [];
    
    return results.map((item: any) => ({
      id: `loc-${item.id.replace(/[^a-zA-Z0-9]/g, '')}`,
      title: item.title || "Library of Congress Item",
      author: item.contributor ? (Array.isArray(item.contributor) ? item.contributor.join(", ") : item.contributor) : "LOC Contributors",
      description: item.description ? (Array.isArray(item.description) ? item.description[0] : item.description) : "An item from the Library of Congress digital collection.",
      category: item.subject ? (Array.isArray(item.subject) ? item.subject[0] : item.subject) : "Historical",
      publication_year: parseInt(item.date?.substring(0, 4)) || 1900,
      cover_image: item.image_url?.[0] || "https://images.unsplash.com/photo-1512820790803-83ca734da794?q=80&w=2974&auto=format&fit=crop",
      rating: 4.9,
      pages: 200 + Math.floor(Math.random() * 300),
      source: 'loc',
      read_link: item.id.startsWith('http') ? item.id : `https://www.loc.gov${item.id}`
    }));
  } catch (error) {
    console.error("LOC fetch error:", error);
    return [];
  }
};