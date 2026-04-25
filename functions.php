<?php
/**
 * Utility functions for the Dare Digital Library
 */

function clean_text($text, $limit = 300) {
    $text = strip_tags($text);
    $text = html_entity_decode($text);
    if (strlen($text) > $limit) {
        $text = substr($text, 0, $limit) . "...";
    }
    return trim($text);
}

function get_source_icon($source) {
    switch ($source) {
        case 'gutenberg': return 'globe';
        case 'dspace': return 'database';
        case 'openstax': return 'graduation-cap';
        case 'openlibrary': return 'library';
        case 'doab': return 'book';
        case 'internetarchive': return 'archive';
        case 'loc': return 'landmark';
        case 'doaj': return 'flask-conical';
        case 'springer': return 'zap';
        case 'crossref': return 'award';
        case 'zim-edu': return 'graduation-cap';
        default: return 'book-open';
    }
}

function get_source_display_name($source) {
    switch ($source) {
        case 'gutenberg': return 'Gutenberg';
        case 'dspace': return 'DSpace';
        case 'openstax': return 'OpenStax';
        case 'openlibrary': return 'Open Library';
        case 'doab': return 'DOAB';
        case 'internetarchive': return 'Internet Archive';
        case 'loc': return 'Library of Congress';
        case 'doaj': return 'DOAJ';
        case 'springer': return 'Springer';
        case 'crossref': return 'CrossRef';
        case 'dare-access': return 'Dare Access';
        case 'zim-edu': return 'Zimbabwe HEI';
        default: return 'Local';
    }
}

/**
 * Zimbabwean Institutions
 */
function get_zim_institutions() {
    return [
        ['id' => 'uz', 'name' => 'University of Zimbabwe'],
        ['id' => 'msu', 'name' => 'Midlands State University'],
        ['id' => 'nust', 'name' => 'National University of Science and Technology'],
        ['id' => 'cut', 'name' => 'Chinhoyi University of Technology'],
        ['id' => 'gzu', 'name' => 'Great Zimbabwe University'],
        ['id' => 'buse', 'name' => 'Bindura University of Science Education'],
        ['id' => 'lsu', 'name' => 'Lupane State University'],
        ['id' => 'zou', 'name' => 'Zimbabwe Open University'],
        ['id' => 'hpoly', 'name' => 'Harare Polytechnic'],
        ['id' => 'bpoly', 'name' => 'Bulawayo Polytechnic'],
    ];
}

/**
 * Education 5.0 Categories
 */
function get_curriculum_categories() {
    return [
        ['id' => 'stem', 'name' => 'STEM & Technology', 'keywords' => ['engineering', 'mathematics', 'computer science', 'physics', 'chemistry']],
        ['id' => 'agriculture', 'name' => 'Agriculture & Environment', 'keywords' => ['farming', 'soil', 'irrigation', 'climate', 'livestock']],
        ['id' => 'heritage', 'name' => 'Heritage & Humanities', 'keywords' => ['history', 'culture', 'zimbabwe', 'philosophy', 'sociology']],
        ['id' => 'health', 'name' => 'Medicine & Health', 'keywords' => ['medical', 'nursing', 'pharmacy', 'public health', 'anatomy']],
        ['id' => 'commerce', 'name' => 'Commerce & Business', 'keywords' => ['economics', 'accounting', 'marketing', 'finance', 'management']],
        ['id' => 'innovation', 'name' => 'Innovation & Industry', 'keywords' => ['industrialization', 'patents', 'entrepreneurship', 'manufacturing']],
    ];
}