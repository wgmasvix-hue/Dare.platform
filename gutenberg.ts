import { Book } from "@/types/book";

const GUTENDEX_URL = "https://gutendex.com/books";

/**
 * Fetches books from the Gutenberg Project via the Gutendex API.
 * @param query Search query
 * @param page Page number (1-indexed)
 */
export const fetchGutenbergBooks = async (query?: string, page: number = 1): Promise<Book[]> => {
  try {
    const baseUrl = query 
      ? `${GUTENDEX_URL}?search=${encodeURIComponent(query)}`
      : GUTENDEX_URL;
    
    const url = baseUrl.includes('?') 
      ? `${baseUrl}&page=${page}`
      : `${baseUrl}?page=${page}`;
      
    const response = await fetch(url);
    if (!response.ok) {
      if (response.status === 404) return []; // No more pages
      throw new Error(`Gutenberg API error: ${response.status}`);
    }
    
    const data = await response.json();
    
    if (!data.results || !Array.isArray(data.results)) {
      return [];
    }
    
    return data.results.map((item: any) => ({
      id: `gutenberg-${item.id}`,
      title: item.title,
      author: item.authors && item.authors.length > 0 
        ? item.authors.map((a: any) => a.name).join(", ") 
        : "Unknown Author",
      description: item.subjects && item.subjects.length > 0
        ? item.subjects.join(". ")
        : "A classic work from the Gutenberg collection.",
      category: (item.bookshelves && item.bookshelves.length > 0)
        ? item.bookshelves[0].replace("Browsing: ", "")
        : (item.subjects && item.subjects.length > 0)
          ? item.subjects[0]
          : "Classic Literature",
      publication_year: 1900,
      cover_image: item.formats["image/jpeg"] || 
                  "https://images.unsplash.com/photo-1543002588-bfa74002ed7e?q=80&w=2735&auto=format&fit=crop",
      rating: 4.8,
      pages: Math.floor(Math.random() * 300) + 150,
      source: 'gutenberg',
      read_link: item.formats["text/html"] || item.formats["text/plain; charset=utf-8"] || item.formats["text/plain"] || ""
    }));
  } catch (error) {
    console.error("Gutenberg fetch error:", error);
    return [];
  }
};

/**
 * Fetches the actual text or HTML content for a book.
 * Due to CORS, we'll use a proxy to ensure reliability.
 */
export const fetchBookContent = async (url: string): Promise<string> => {
  if (!url) return "Content not available.";
  
  try {
    // We use a CORS proxy because Gutenberg servers usually restrict direct fetch
    const proxyUrl = `https://api.allorigins.win/raw?url=${encodeURIComponent(url)}`;
    
    const response = await fetch(proxyUrl);
    if (!response.ok) throw new Error("Failed to fetch content from proxy");
    
    let text = await response.text();
    
    // If it's HTML, we don't want to strip Gutenberg boilerplate the same way as text
    // because it might break tags. But we can do some basic cleanup if it's text.
    if (!url.includes(".html") && !text.includes("<!DOCTYPE html>")) {
        // Basic cleanup of Gutenberg boilerplate if it's plain text
        if (text.includes("*** START OF THE PROJECT GUTENBERG EBOOK")) {
          const parts = text.split("*** START OF THE PROJECT GUTENBERG EBOOK");
          if (parts.length > 1) {
            text = parts[1];
          }
          const endParts = text.split("*** END OF THE PROJECT GUTENBERG EBOOK");
          if (endParts.length > 0) {
            text = endParts[0];
          }
        }
        
        // Fallback cleanup if the previous split fails for some reason
        if (text.includes("START OF THIS PROJECT GUTENBERG EBOOK")) {
            const parts = text.split("START OF THIS PROJECT GUTENBERG EBOOK");
            if (parts.length > 1) {
                text = parts[1];
            }
            const endParts = text.split("END OF THIS PROJECT GUTENBERG EBOOK");
            if (endParts.length > 0) {
                text = endParts[0];
            }
        }
    }
    
    return text.trim();
  } catch (error) {
    console.error("Error fetching book content:", error);
    
    // Second attempt without proxy if the first fails, some sources might allow direct access
    try {
        const response = await fetch(url);
        if (!response.ok) throw new Error("Direct fetch failed too");
        const text = await response.text();
        return text.trim();
    } catch (directError) {
        console.error("Direct fetch failed too:", directError);
        return "Sorry, we could not load the book content at this time. Please try the 'Source' button in the book details instead.";
    }
  }
};