<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        try {
            $sortBy = $request->get("sort_by", 'title');
            $sortDir = $request->get('sort_dir', 'asc');
            $validSorts = ["id", "title", "author", "publisher", "year", "isbn", "stock"];
            $categoryId = $request->get('category');

            if (!in_array($sortBy, $validSorts)) {
                $sortBy = "title";
            }

            $booksQuery = Book::with(['categories', 'borrows.user']);
            if ($categoryId) {
                $booksQuery->whereHas('categories', function ($query) use ($categoryId) {
                    $query->where('categories.id', $categoryId);
                });
            }

            $books = $booksQuery
                ->orderBy($sortBy, $sortDir)
                ->paginate(20)
                ->appends($request->all());

            $categories = Category::withCount('books')->get();

            return view("admin.books.index", compact("books", "categories", "sortBy", "sortDir"));
        } catch (Exception $e) {
            // Logging error (opsional)
            Log::error("Error fetching books: " . $e->getMessage());

            // Redirect ke halaman error atau kembali dengan pesan
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengambil data buku.');
        }
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
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'cover' => 'nullable|image|max:2048',
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

    // public function autocomplete(Request $request)
    // {
    //     $q = $request->get('q');
    //     $filter = $request->get('filter', 'title');

    //     $query = Book::query();

    //     if (in_array($filter, ['title', 'isbn', 'author', 'publisher'])) {
    //         $query->where($filter, 'like', $q . '%');
    //     }

    //     $books = $query->latest()->limit(10)->get();

    //     return response()->json($books->map(function ($book) {
    //         return [
    //             'id' => $book->id,
    //             'title' => $book->title,
    //             'author' => $book->author,
    //             'isbn' => $book->isbn,
    //             'stock' => $book->stock,
    //         ];
    //     }));
    // }

    public function search(Request $request)
    {
        $query = $request->get('query');
        $books = Book::where('title', 'like',   $query . '%')
            ->orWhere('author', 'like',  $query . '%')
            ->orWhere('isbn', 'like',  $query . '%')
            ->orWhere('publisher', 'like',  $query . '%')->orderBy('created_at', 'desc')->take(10)->get();

        return response()->json($books);
    }

    public function show(Book $book)
    {
        return view('admin.books.show', compact('book'));
    }
}
