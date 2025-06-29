<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with(['categories' => function ($query) {
            $query->select('categories.id', 'name')->limit(3);
        }])->paginate(20);

        return view("admin.books.index", compact("books"));
    }
}
