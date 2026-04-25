<?php
/**
 * Refactored AI Analysis for Dare Digital Library
 * Aligned with Education 5.0: Teaching, Research, Community Service, Innovation, and Industrialization.
 */

/**
 * Performs keyword-based analysis of the book content to simulate deep AI understanding.
 */
function perform_in_depth_analysis($book) {
    $content = strtolower(($book['content'] ?? '') . ' ' . ($book['description'] ?? '') . ' ' . ($book['title'] ?? ''));
    
    $keywords = [
        'heritage' => ['culture', 'history', 'tradition', 'indigenous', 'ancestry', 'heritage', 'great zimbabwe', 'archaeology'],
        'technology' => ['digital', 'software', 'computer', 'engineering', 'machine', 'innovation', 'data', 'internet', 'tech'],
        'agriculture' => ['farm', 'crop', 'soil', 'water', 'irrigation', 'climate', 'livestock', 'food', 'nutrition'],
        'economy' => ['money', 'business', 'market', 'trade', 'industry', 'manufacturing', 'growth', 'finance'],
        'health' => ['medicine', 'doctor', 'disease', 'health', 'biology', 'care', 'wellness'],
        'mining' => ['mineral', 'mining', 'lithium', 'gold', 'extraction', 'geology', 'earth', 'rock']
    ];

    $detected = [];
    foreach ($keywords as $key => $list) {
        foreach ($list as $word) {
            if (strpos($content, $word) !== false) {
                $detected[] = $key;
                break;
            }
        }
    }

    return !empty($detected) ? $detected : ['general academic'];
}

/**
 * Maps analysis results to Education 5.0 principles.
 */
function map_to_education_5_principles($analysis) {
    $contextMap = [
        'heritage' => "Leveraging Zimbabwe's rich cultural history and Great Zimbabwe architectural logic to inform modern structural and social design.",
        'technology' => "Bridging the digital divide by applying global technological frameworks to local Zimbabwean infrastructure bottlenecks.",
        'agriculture' => "Utilizing climate-smart agriculture and indigenous small grains (Zviyo/Mapfunde) to ensure national food security under Vision 2030.",
        'economy' => "Transitioning from a primary resource exporter to a value-added manufacturing hub through heritage-based industrialization.",
        'health' => "Integrating traditional pharmacological knowledge with modern medical research to create a robust national health ecosystem.",
        'mining' => "Moving beyond extraction to lithium and platinum value-addition within Zimbabwe to drive the 4th Industrial Revolution.",
        'general academic' => "Contextualizing global knowledge within the 'Kuziva, Kugona, neKuita' (Knowing, Being, and Doing) framework of Education 5.0."
    ];

    return $contextMap[$analysis[0]] ?? $contextMap['general academic'];
}

/**
 * Generates an Education 5.0 Study Guide (Refactored for deep analysis)
 */
function generate_education_5_study_guide($book) {
    // Simulate latency for "analysis"
    usleep(1500000);

    $analysis = perform_in_depth_analysis($book);
    $heritageContext = map_to_education_5_principles($analysis);
    $mainTheme = $analysis[0];

    return [
        'bookId' => $book['id'],
        'heritage_context' => $heritageContext,
        'pillars' => [
            'teaching' => [
                'title' => 'Teaching',
                'content' => "Adapt {$book['title']}'s core findings into a curriculum that emphasizes outcome-based education. Focus on how {$mainTheme} theories manifest in the Zimbabwean landscape.",
                'icon' => 'GraduationCap',
                'actionable_step' => 'Create a tutorial series that challenges students to find local parallels.'
            ],
            'research' => [
                'title' => 'Research',
                'content' => "Conduct a gap analysis between {$book['author']}'s conclusions and the current state of {$mainTheme} in Zimbabwe.",
                'icon' => 'Search',
                'actionable_step' => 'Draft a research proposal for a case study centered on a Zimbabwean province.'
            ],
            'community_service' => [
                'title' => 'Community Service',
                'content' => "How can the {$mainTheme} principles in this work be simplified for community outreach to empower rural cooperatives?",
                'icon' => 'Users',
                'actionable_step' => 'Design a 1-day community workshop layout.'
            ],
            'innovation' => [
                'title' => 'Innovation',
                'content' => "Innovate by blending the methodology with Zimbabwean resources to 'leapfrog' traditional stages of development.",
                'icon' => 'Lightbulb',
                'actionable_step' => 'Identify a specific Zimbabwean industrial bottleneck this text could solve.'
            ],
            'industrialization' => [
                'title' => 'Industrialization',
                'content' => "Analyze how the theories here can be commercialized to create new value chains in Zimbabwe.",
                'icon' => 'Factory',
                'actionable_step' => 'Develop a business model for a start-up that applies this research.'
            ]
        ],
        'last_updated' => date('c')
    ];
}

/**
 * Generates a book summary based on analysis.
 */
function generate_book_summary($book) {
    $analysis = perform_in_depth_analysis($book);
    $isSpringer = (isset($book['source']) && ($book['source'] === 'springer' || $book['source'] === 'crossref'));
    
    $themes = array_map('ucfirst', array_slice($analysis, 0, 3));
    $themeStr = implode(', ', $themes);

    $brief = "Through an in-depth AI analysis of \\"{$book['title']}\\", we have identified core themes relating to {$themeStr}.";
    
    if ($isSpringer) {
        $brief = "[Springer Nature AI Analysis]: \\"{$book['title']}\\" provides a critical examination of {$themes[0]} within {$book['category']}.";
    }

    return [
        'brief' => $brief,
        'themes' => $themes,
        'whyRead' => "This resource is vital for Zimbabwean scholars because it addresses the intersection of global {$analysis[0]} trends and local needs.",
        'isSpringerAI' => $isSpringer
    ];
}