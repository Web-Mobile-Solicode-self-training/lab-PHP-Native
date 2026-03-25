<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BookService;

class BookController extends Controller
{
    protected BookService $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function index(Request $request)
    {
        $query = $request->input('q', '');
        $books = $this->bookService->searchBooks($query);

        return view('index', [
            'books' => $books,
            'searchQuery' => $query ?: 'Software Engineering',
            'resultsTitle' => $query ? "{$query} Books" : 'Trending Now',
        ]);
    }
}
