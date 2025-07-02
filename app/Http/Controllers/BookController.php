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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'publisher' => 'nullable|string',
            'year' => 'nullable|integer',
            'isbn' => 'nullable|string',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'stock' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'cover' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $book = Book::create($validated);

        if ($request->has('categories')) {
            $book->categories()->sync($request->categories);
        }

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan.');
    }
}
