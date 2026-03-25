<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BookService
{
    /**
     * Search for books using the Google Books API
     *
     * @param string $query
     * @return array
     */
    public function searchBooks(string $query): array
    {
        if (empty($query)) {
            $query = 'Software Engineering';
        }

        try {
            $response = Http::get('https://www.googleapis.com/books/v1/volumes', [
                'q' => $query,
                'maxResults' => 25
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['items'] ?? [];
            }
        } catch (\Exception $e) {
            // Silently handle error for now
        }

        return [];
    }
}
