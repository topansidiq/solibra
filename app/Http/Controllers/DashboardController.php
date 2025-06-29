<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::all();
        $categories = Category::all();
        $books_count = Book::count();
        $categories_count = Category::count();
        $selectedCategories = $request->input("categories", []);
        $categoriesQuuery = Category::query();

        if (!empty($selectedCategories)) {
            $categoriesQuuery->whereHas("categories", function ($query) use ($selectedCategories) {
                $query->whereIn("id", $selectedCategories);
            });
        }

        $latestBooks = Book::with('categories')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view("admin.dashboard.index", compact("books", "categories", "latestBooks", "books_count", "categories_count", "selectedCategories"));
    }

    public function addBook(Request $request)
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
            'cover' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $book = Book::create($validated);

        if ($request->has('categories')) {
            $book->categories()->sync($request->categories);
        }

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil ditambahkan.');
    }
}
