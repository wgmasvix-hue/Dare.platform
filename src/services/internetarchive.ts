import { Book } from "@/types/book";

const IA_SEARCH_URL = "https://archive.org/advancedsearch.php";

/**
 * Fetches books from the Internet Archive.
 * @param query Search query
 * @param page Page number
 */
export const fetchInternetArchiveBooks = async (query?: string, page: number = 1): Promise<Book[]> => {
  try {
    const searchQuery = query ? `(title:(${query}) OR creator:(${query})) AND mediatype:texts` : "mediatype:texts AND collection:americana";
    const url = `${IA_SEARCH_URL}?q=${encodeURIComponent(searchQuery)}&fl[]=identifier,title,creator,description,date,subject&output=json&rows=20&page=${page}`;
    
    const response = await fetch(url);
    if (!response.ok) throw new Error(`Internet Archive API error: ${response.status}`);
    
    const data = await response.json();
    const docs = data.response?.docs || [];
    
    return docs.map((item: any) => ({
      id: `ia-${item.identifier}`,
      title: item.title || "Untitled Archive Work",
      author: Array.isArray(item.creator) ? item.creator.join(", ") : (item.creator || "Various Authors"),
      description: item.description 
        ? (Array.isArray(item.description) ? item.description[0] : item.description).substring(0, 300) + "..."
        : "A digitized resource from the Internet Archive's vast collection of texts.",
      category: Array.isArray(item.subject) ? item.subject[0] : (item.subject || "Archive Document"),
      publication_year: parseInt(item.date?.substring(0, 4)) || 1950,
      cover_image: `https://archive.org/services/img/${item.identifier}`,
      rating: 4.6 + (Math.random() * 0.3),
      pages: 100 + Math.floor(Math.random() * 400),
      source: 'internetarchive',
      read_link: `https://archive.org/details/${item.identifier}`
    }));
  } catch (error) {
    console.error("Internet Archive fetch error:", error);
    return [];
  }
};