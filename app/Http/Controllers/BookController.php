<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('books')->get();
        $books = Book::with(['categories' => function ($query) {
            $query->select('categories.id', 'name')->limit(3);
        }])->paginate(20);

        return view("admin.books.index", compact("books", "categories"));
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

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'isbn' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'stock' => 'nullable|integer|min:0',
        ]);

        $book->update($validated);

        return response()->json([
            'message' => 'Buku berhasil diperbarui!',
            'book' => $book,
        ]);
    }

    public function destroy(Book $book)
    {
        // Hapus cover jika ada
        if ($book->cover && Storage::exists('public/cover/' . $book->cover)) {
            Storage::delete('public/cover/' . $book->cover);
        }

        // Detach kategori
        $book->categories()->detach();

        // Hapus buku
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus.');
    }
}
