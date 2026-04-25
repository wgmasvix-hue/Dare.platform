<?php
/**
 * Source fetching logic for the Dare Digital Library
 */

function fetch_api_data($url) {
    $options = [
        "http" => [
            "method" => "GET",
            "header" => "User-Agent: DareDigitalLibrary/1.0 (Contact: admin@example.com)\\r
"
        ]
    ];
    $context = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);
    if ($response === false) return null;
    return json_decode($response, true);
}

function fetch_gutenberg_books($query = "", $page = 1) {
    $baseUrl = "https://gutendex.com/books";
    $url = $query ? "$baseUrl?search=" . urlencode($query) : $baseUrl;
    $url .= (strpos($url, '?') !== false ? '&' : '?') . "page=$page";
    
    $data = fetch_api_data($url);
    if (!$data || !isset($data['results'])) return [];

    $books = [];
    foreach ($data['results'] as $item) {
        $authors = isset($item['authors']) ? implode(", ", array_map(fn($a) => $a['name'], $item['authors'])) : "Unknown Author";
        $category = (isset($item['bookshelves']) && count($item['bookshelves']) > 0) 
            ? str_replace("Browsing: ", "", $item['bookshelves'][0]) 
            : (isset($item['subjects']) && count($item['subjects']) > 0 ? $item['subjects'][0] : "Classic Literature");
        
        $books[] = [
            'id' => "gutenberg-" . $item['id'],
            'title' => $item['title'],
            'author' => $authors,
            'description' => isset($item['subjects']) ? implode(". ", $item['subjects']) : "A classic work from the Gutenberg collection.",
            'category' => $category,
            'publication_year' => 1900,
            'cover_image' => $item['formats']['image/jpeg'] ?? "https://images.unsplash.com/photo-1543002588-bfa74002ed7e?q=80&w=2735&auto=format&fit=crop",
            'rating' => 4.8,
            'pages' => rand(150, 450),
            'source' => 'gutenberg',
            'read_link' => $item['formats']['text/html'] ?? $item['formats']['text/plain; charset=utf-8'] ?? $item['formats']['text/plain'] ?? ""
        ];
    }
    return $books;
}

function fetch_openstax_books($query = "", $page = 1) {
    $limit = 20;
    $offset = ($page - 1) * $limit;
    $url = "https://openstax.org/api/v2/pages/?type=book.Book&fields=title,cover_url,description,subjects,authors,id,meta&limit=$limit&offset=$offset";
    if ($query) $url .= "&search=" . urlencode($query);

    $data = fetch_api_data($url);
    if (!$data || !isset($data['items'])) return [];

    $books = [];
    foreach ($data['items'] as $item) {
        $author = (isset($item['authors']) && count($item['authors']) > 0) ? implode(", ", array_map(fn($a) => $a['name'], $item['authors'])) : "OpenStax Authors";
        $category = (isset($item['subjects']) && count($item['subjects']) > 0) ? $item['subjects'][0]['name'] : "Open Textbook";
        $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', $item['title']));

        $books[] = [
            'id' => "openstax-" . $item['id'],
            'title' => $item['title'],
            'author' => $author,
            'description' => clean_text($item['description'] ?? "A peer-reviewed, openly licensed textbook from OpenStax."),
            'category' => $category,
            'publication_year' => 2024,
            'cover_image' => $item['cover_url'] ?? "https://images.unsplash.com/photo-1497633762265-9d179a990aa6?q=80&w=2946&auto=format&fit=crop",
            'rating' => 4.8 + (rand(0, 20) / 100),
            'pages' => rand(350, 650),
            'source' => 'openstax',
            'read_link' => "https://openstax.org/details/books/$slug"
        ];
    }
    return $books;
}

function fetch_dspace_books($query = "", $page = 0, $size = 20) {
    $searchUrl = "https://demo7.dspace.org/server/api/discover/search/objects?query=" . urlencode($query ?: "*") . "&size=$size&page=$page";
    $data = fetch_api_data($searchUrl);
    if (!$data || !isset($data['_embedded']['searchResult']['_embedded']['objects'])) return [];

    $books = [];
    foreach ($data['_embedded']['searchResult']['_embedded']['objects'] as $obj) {
        $item = $obj['_embedded']['indexableObject'];
        $metadata = $item['metadata'] ?? [];
        
        $getMetadata = function($key) use ($metadata) {
            return (isset($metadata[$key]) && count($metadata[$key]) > 0) ? $metadata[$key][0]['value'] : null;
        };

        $books[] = [
            'id' => "dspace-" . ($item['uuid'] ?? uniqid()),
            'title' => $getMetadata("dc.title") ?: "Untitled Work",
            'author' => $getMetadata("dc.contributor.author") ?: $getMetadata("dc.creator") ?: "Unknown Author",
            'description' => $getMetadata("dc.description.abstract") ?: $getMetadata("dc.description") ?: "Digital object from DSpace library.",
            'category' => $getMetadata("dc.subject") ?: "Digital Library Item",
            'publication_year' => (int)substr($getMetadata("dc.date.issued") ?: "2024", 0, 4),
            'cover_image' => "https://images.unsplash.com/photo-1532012197267-da84d127e765?q=80&w=2787&auto=format&fit=crop",
            'rating' => 4.5,
            'pages' => rand(20, 70),
            'source' => 'dspace',
            'read_link' => $getMetadata("dc.identifier.uri") ?: ""
        ];
    }
    return $books;
}

function fetch_openlibrary_books($query = "", $page = 1) {
    $searchQuery = $query ?: "classic";
    $url = "https://openlibrary.org/search.json?q=" . urlencode($searchQuery) . "&page=$page&limit=20";
    $data = fetch_api_data($url);
    if (!$data || !isset($data['docs'])) return [];

    $books = [];
    foreach ($data['docs'] as $item) {
        $coverId = $item['cover_i'] ?? null;
        $coverImage = $coverId ? "https://covers.openlibrary.org/b/id/$coverId-L.jpg" : "https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=2787&auto=format&fit=crop";
        $books[] = [
            'id' => "openlib-" . str_replace('/works/', '', $item['key']),
            'title' => $item['title'],
            'author' => isset($item['author_name']) ? implode(", ", $item['author_name']) : "Unknown Author",
            'description' => $item['first_sentence'][0] ?? "A work published in " . ($item['first_publish_year'] ?? 'the past') . ". Explore this classic from the Open Library.",
            'category' => $item['subject'][0] ?? "General Literature",
            'publication_year' => $item['first_publish_year'] ?? 2000,
            'cover_image' => $coverImage,
            'rating' => 4.5 + (rand(0, 50) / 100),
            'pages' => $item['number_of_pages_median'] ?? 250,
            'source' => 'openlibrary',
            'read_link' => "https://openlibrary.org" . $item['key']
        ];
    }
    return $books;
}

function fetch_internetarchive_books($query = "", $page = 1) {
    $searchQuery = $query ? "(title:($query) OR creator:($query)) AND mediatype:texts" : "mediatype:texts AND collection:americana";
    $url = "https://archive.org/advancedsearch.php?q=" . urlencode($searchQuery) . "&fl[]=identifier,title,creator,description,date,subject&output=json&rows=20&page=$page";
    $data = fetch_api_data($url);
    if (!$data || !isset($data['response']['docs'])) return [];

    $books = [];
    foreach ($data['response']['docs'] as $item) {
        $books[] = [
            'id' => "ia-" . $item['identifier'],
            'title' => $item['title'] ?? "Untitled Archive Work",
            'author' => is_array($item['creator'] ?? '') ? implode(", ", $item['creator']) : ($item['creator'] ?? "Various Authors"),
            'description' => clean_text(is_array($item['description'] ?? '') ? $item['description'][0] : ($item['description'] ?? "A digitized resource from Internet Archive.")),
            'category' => is_array($item['subject'] ?? '') ? $item['subject'][0] : ($item['subject'] ?? "Archive Document"),
            'publication_year' => (int)substr($item['date'] ?? "1950", 0, 4),
            'cover_image' => "https://archive.org/services/img/" . $item['identifier'],
            'rating' => 4.6 + (rand(0, 30) / 100),
            'pages' => rand(100, 500),
            'source' => 'internetarchive',
            'read_link' => "https://archive.org/details/" . $item['identifier']
        ];
    }
    return $books;
}

function fetch_loc_books($query = "", $page = 1) {
    $searchQuery = $query ?: "literature";
    $url = "https://www.loc.gov/books/?q=" . urlencode($searchQuery) . "&fo=json&at=results&sp=$page&c=20";
    $data = fetch_api_data($url);
    if (!$data || !isset($data['results'])) return [];

    $books = [];
    foreach ($data['results'] as $item) {
        $books[] = [
            'id' => "loc-" . preg_replace('/[^a-zA-Z0-9]/', '', $item['id']),
            'title' => $item['title'] ?? "Library of Congress Item",
            'author' => is_array($item['contributor'] ?? '') ? implode(", ", $item['contributor']) : ($item['contributor'] ?? "LOC Contributors"),
            'description' => clean_text(is_array($item['description'] ?? '') ? $item['description'][0] : ($item['description'] ?? "An item from Library of Congress.")),
            'category' => is_array($item['subject'] ?? '') ? $item['subject'][0] : ($item['subject'] ?? "Historical"),
            'publication_year' => (int)substr($item['date'] ?? "1900", 0, 4),
            'cover_image' => $item['image_url'][0] ?? "https://images.unsplash.com/photo-1512820790803-83ca734da794?q=80&w=2974&auto=format&fit=crop",
            'rating' => 4.9,
            'pages' => rand(200, 500),
            'source' => 'loc',
            'read_link' => strpos($item['id'], 'http') === 0 ? $item['id'] : "https://www.loc.gov" . $item['id']
        ];
    }
    return $books;
}

function fetch_doaj_books($query = "", $page = 1) {
    $searchQuery = $query ?: "nature";
    $url = "https://doaj.org/api/v2/search/articles/" . urlencode($searchQuery) . "?page=$page&pageSize=20";
    $data = fetch_api_data($url);
    if (!$data || !isset($data['results'])) return [];

    $books = [];
    foreach ($data['results'] as $item) {
        $bibjson = $item['bibjson'];
        $books[] = [
            'id' => "doaj-" . $item['id'],
            'title' => $bibjson['title'] ?? "Open Access Article",
            'author' => isset($bibjson['author']) ? implode(", ", array_map(fn($a) => $a['name'], $bibjson['author'])) : "Various Researchers",
            'description' => clean_text($bibjson['abstract'] ?? "A peer-reviewed open access research paper."),
            'category' => $bibjson['subject'][0]['term'] ?? "Scientific Research",
            'publication_year' => (int)($bibjson['year'] ?? 2024),
            'cover_image' => "https://images.unsplash.com/photo-1532012197267-da84d127e765?q=80&w=2787&auto=format&fit=crop",
            'rating' => 4.7,
            'pages' => rand(30, 130),
            'source' => 'doaj',
            'read_link' => $bibjson['link'][0]['url'] ?? "https://doaj.org/article/" . $item['id']
        ];
    }
    return $books;
}

function fetch_crossref_books($query = "", $page = 1) {
    $rows = 20;
    $offset = ($page - 1) * $rows;
    $searchQuery = $query ?: "knowledge";
    $url = "https://api.crossref.org/works?query=" . urlencode($searchQuery) . "&filter=type:monograph&rows=$rows&offset=$offset";
    $data = fetch_api_data($url);
    if (!$data || !isset($data['message']['items'])) return [];

    $books = [];
    foreach ($data['message']['items'] as $item) {
        $authors = isset($item['author']) ? implode(", ", array_map(fn($a) => ($a['given'] ?? '') . " " . ($a['family'] ?? ''), $item['author'])) : "Academic Contributor";
        $year = $item['published']['date-parts'][0][0] ?? 2023;
        $books[] = [
            'id' => "crossref-" . str_replace('/', '-', $item['DOI']),
            'title' => $item['title'][0] ?? "Research Work",
            'author' => $authors,
            'description' => clean_text($item['abstract'] ?? "A contemporary academic monograph."),
            'category' => $item['subject'][0] ?? "Academic Research",
            'publication_year' => $year,
            'cover_image' => "https://images.unsplash.com/photo-1532012197267-da84d127e765?q=80&w=2787&auto=format&fit=crop",
            'rating' => 4.2 + (rand(0, 80) / 100),
            'pages' => rand(150, 450),
            'source' => 'crossref',
            'read_link' => $item['URL'] ?? "#"
        ];
    }
    return $books;
}

function get_springer_mock_books($query, $page) {
    $categories = ["Advanced Physics", "Molecular Biology", "Artificial Intelligence", "Climate Change", "Neurology", "Global Economics"];
    $authors = ["Dr. Sarah Chen", "Marcus Thorne", "Elena Rodriguez", "Hiroshi Tanaka", "Sarah Jenkins"];
    $books = [];
    for ($i = 0; $i < 12; $i++) {
        $category = $categories[$i % count($categories)];
        $books[] = [
            'id' => "springer-mock-$page-$i",
            'title' => "$category in the 21st Century: Volume " . ($page + $i),
            'author' => $authors[$i % count($authors)],
            'description' => "A groundbreaking comprehensive study on " . strtolower($category) . ".",
            'category' => $category,
            'publication_year' => 2024 - ($i % 3),
            'cover_image' => [
                "https://images.unsplash.com/photo-1532187875605-1ef6c237a1e0?q=80&w=2940&auto=format&fit=crop",
                "https://images.unsplash.com/photo-1614850523296-e81109eead9a?q=80&w=2940&auto=format&fit=crop",
                "https://images.unsplash.com/photo-1581093458791-9f3c3900df4b?q=80&w=2940&auto=format&fit=crop",
                "https://images.unsplash.com/photo-1518152006812-edab29b069ac?q=80&w=2940&auto=format&fit=crop"
            ][$i % 4],
            'rating' => 4.7 + (rand(0, 30) / 100),
            'pages' => 350 + ($i * 20),
            'source' => 'springer',
            'read_link' => "#"
        ];
    }
    return $books;
}

function fetch_springer_books($query = "", $page = 1) {
    // Springer often requires an API key, so we use mocks by default as in the original code
    return get_springer_mock_books($query, $page);
}