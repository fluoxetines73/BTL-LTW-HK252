<?php
/**
 * Configuration for TMDB movie seed generator
 */
return [
    'tmdb_api_key' => getenv('TMDB_API_KEY') ?: '',
    'tmdb_base_url' => 'https://api.themoviedb.org/3',
    'image_base_url' => 'https://image.tmdb.org/t/p/original',
    'youtube_base_url' => 'https://www.youtube.com/watch?v=',
    'date_from' => '2026-04-01',
    'date_to' => '2026-07-31',
    'region' => 'VN',
    'language' => 'vi-VN',
    // Fallback starting ID if DB auto-detection fails (not needed in most cases)
    'starting_movie_id' => 7,
    'output_file' => __DIR__ . '/../database/movie_seed_tmdb.sql',
];
