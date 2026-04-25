<?php
/**
 * API Endpoint for Dare Digital Library
 */
header('Content-Type: application/json');
require_once 'includes/functions.php';
require_once 'includes/sources.php';
require_once 'includes/ai.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'fetch_books':
        $query = $_GET['query'] ?? '';
        $page = (int)($_GET['page'] ?? 1);
        $filter = $_GET['filter'] ?? 'all';
        $institution = $_GET['institution'] ?? '';
        $curriculum = $_GET['curriculum'] ?? '';
        
        // If curriculum is selected, append keywords to query
        if ($curriculum) {
            $categories = get_curriculum_categories();
            foreach ($categories as $cat) {
                if ($cat['id'] === $curriculum) {
                    $query .= " " . implode(" ", $cat['keywords']);
                    break;
                }
            }
        }

        $books = [];
        
        if ($filter === 'all' || $filter === 'gutenberg') $books = array_merge($books, fetch_gutenberg_books($query, $page));
        if ($filter === 'all' || $filter === 'openstax') $books = array_merge($books, fetch_openstax_books($query, $page));
        if ($filter === 'all' || $filter === 'dspace') $books = array_merge($books, fetch_dspace_books($query, $page - 1));
        if ($filter === 'all' || $filter === 'openlibrary') $books = array_merge($books, fetch_openlibrary_books($query, $page));
        if ($filter === 'all' || $filter === 'internetarchive') $books = array_merge($books, fetch_internetarchive_books($query, $page));
        if ($filter === 'all' || $filter === 'loc') $books = array_merge($books, fetch_loc_books($query, $page));
        if ($filter === 'all' || $filter === 'doaj') $books = array_merge($books, fetch_doaj_books($query, $page));
        if ($filter === 'all' || $filter === 'springer') $books = array_merge($books, fetch_springer_books($query, $page));
        if ($filter === 'all' || $filter === 'crossref') $books = array_merge($books, fetch_crossref_books($query, $page));

        // Deduplicate by ID
        $unique_books = [];
        foreach ($books as $book) {
            $unique_books[$book['id']] = $book;
        }
        $books = array_values($unique_books);
        
        // Sort by publication year
        usort($books, function($a, $b) {
            return $b['publication_year'] <=> $a['publication_year'];
        });

        echo json_encode(['books' => $books]);
        break;

    case 'get_config':
        echo json_encode([
            'institutions' => get_zim_institutions(),
            'curriculum' => get_curriculum_categories()
        ]);
        break;

    case 'generate_summary':
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || !isset($data['book'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request']);
            exit;
        }
        echo json_encode(generate_book_summary($data['book']));
        break;

    case 'generate_study_guide':
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || !isset($data['book'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request']);
            exit;
        }
        echo json_encode(generate_education_5_study_guide($data['book']));
        break;

    case 'fetch_content':
        $url = $_GET['url'] ?? '';
        if (!$url) {
            echo json_encode(['content' => 'No URL provided']);
            exit;
        }
        
        $options = ["http" => ["header" => "User-Agent: DareDigitalLibrary/1.0\\r\
"]];
        $context = stream_context_create($options);
        $content = @file_get_contents($url, false, $context);
        
        if ($content === false) {
            echo json_encode(['content' => 'Failed to fetch content.']);
        } else {
            // Basic cleanup for Gutenberg
            if (strpos($content, "*** START OF") !== false) {
                $parts = explode("*** START OF", $content);
                if (count($parts) > 1) {
                    $content = explode("*** END OF", $parts[1])[0];
                }
            }
            echo json_encode(['content' => trim($content)]);
        }
        break;

    default:
        echo json_encode(['error' => 'Unknown action']);
}