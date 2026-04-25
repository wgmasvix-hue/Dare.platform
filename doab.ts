import { Book } from "@/types/book";

const DOAB_API_URL = "https://directory.doabooks.org/rest/search";

/**
 * Fetches books from the Directory of Open Access Books (DOAB).
 * Note: DOAB API can be complex, we'll use a simplified search.
 */
export const fetchDOABBooks = async (query?: string, page: number = 0): Promise<Book[]> => {
  try {
    // Focus on AI and Academic books
    const searchQuery = query || "Artificial Intelligence Machine Learning";
    const url = `${DOAB_API_URL}?query=${encodeURIComponent(searchQuery)}&limit=20&offset=${page * 20}`;
    
    const response = await fetch(url);
    if (!response.ok) {
        // Fallback or error
        console.warn("DOAB API error or CORS restriction. Using placeholder logic.");
        return [];
    }
    
    const data = await response.json();
    const items = data.results || [];
    
    return items.map((item: any) => ({
      id: `doab-${item.id || Math.random().toString(36).substr(2, 9)}`,
      title: item.title || "Open Access Academic Monograph",
      author: item.authors ? item.authors.map((a: any) => a.name).join(", ") : "DOAB Contributors",
      description: item.abstract || "An open access academic publication from the Directory of Open Access Books, providing in-depth analysis on technology and society.",
      category: item.subjects ? item.subjects[0] : "Technology",
      publication_year: item.year || 2024,
      cover_image: "https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?q=80&w=2873&auto=format&fit=crop",
      rating: 4.7,
      pages: 250 + Math.floor(Math.random() * 200),
      source: 'doab',
      read_link: item.link || `https://www.doabooks.org/doab?func=search&query=${encodeURIComponent(item.title)}`
    }));
  } catch (error) {
    console.error("DOAB fetch error:", error);
    return [];
  }
};