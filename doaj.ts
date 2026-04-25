import { Book } from "@/types/book";

const DOAJ_API_URL = "https://doaj.org/api/v2/search/articles";

/**
 * Fetches articles/books from the Directory of Open Access Journals (DOAJ).
 * @param query Search query
 * @param page Page number
 */
export const fetchDOAJBooks = async (query?: string, page: number = 1): Promise<Book[]> => {
  try {
    // Focus on AI and high-impact journals
    const searchQuery = query || "Artificial Intelligence Machine Learning";
    const url = `${DOAJ_API_URL}/${encodeURIComponent(searchQuery)}?page=${page}&pageSize=20`;
    
    const response = await fetch(url);
    if (!response.ok) throw new Error(`DOAJ API error: ${response.status}`);
    
    const data = await response.json();
    const results = data.results || [];
    
    return results.map((item: any) => {
      const bibjson = item.bibjson;
      return {
        id: `doaj-${item.id}`,
        title: bibjson.title || "Open Access Peer-Reviewed Article",
        author: bibjson.author ? bibjson.author.map((a: any) => a.name).join(", ") : "Various Researchers",
        description: bibjson.abstract || "A peer-reviewed open access research paper published in a reputable journal indexed by DOAJ.",
        category: bibjson.subject ? bibjson.subject[0].term : "Computer Science",
        publication_year: parseInt(bibjson.year) || 2024,
        cover_image: "https://images.unsplash.com/photo-1532012197267-da84d127e765?q=80&w=2787&auto=format&fit=crop",
        rating: 4.8,
        pages: 30 + Math.floor(Math.random() * 100),
        source: 'doaj',
        read_link: bibjson.link ? bibjson.link[0].url : `https://doaj.org/article/${item.id}`
      };
    });
  } catch (error) {
    console.error("DOAJ fetch error:", error);
    return [];
  }
};