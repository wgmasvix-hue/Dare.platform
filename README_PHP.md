# Dare Digital Library (PHP Version)

This is the PHP version of the Dare Digital Library, converted from a React project for easy deployment on local web servers like Webzim.

## Features
- **Multi-source Aggregation**: Fetches books from Gutenberg, OpenStax, Open Library, Internet Archive, Library of Congress, etc.
- **AI-Powered Insights**: Simulated AI summaries for every book.
- **Embedded Reader**: Direct reading access for open-text resources.
- **Modern UI**: Built with Tailwind CSS and Alpine.js for a smooth, single-page experience.

## Installation
1. Upload all files to your web server (e.g., in a folder named `library`).
2. Ensure your server has PHP 7.4 or 8.x installed.
3. Access the folder via your browser (e.g., `http://localhost/library/index.php`).

## File Structure
- `index.php`: Main dashboard UI.
- `api.php`: Handles all backend requests (API proxy).
- `includes/`:
  - `sources.php`: logic for fetching from various libraries.
  - `ai.php`: logic for AI summary generation.
  - `functions.php`: general utilities.