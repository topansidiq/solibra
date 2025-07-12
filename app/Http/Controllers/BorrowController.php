<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortBy = $request->get("sort_by", 'borrowed_at');
        $sortDir = $request->get('sort_dir', 'desc');

        $borrows = Borrow::with(['book', 'user'])
            ->orderBy($sortBy, $sortDir)
            ->paginate(20)
            ->appends($request->all());

        $categories = Category::withCount('books')
            ->orderBy('name', 'asc')
            ->get();
        $books = Book::with(['categories'])
            ->orderBy('title', 'asc')
            ->get();

        $users = User::all();

        return view("admin.borrows.index", compact("borrows", "sortBy", "sortDir", "categories", "books", "users"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function confirm(Borrow $borrow)
    {
        $now = now();

        $borrow->update([
            'status' => 'confirmed',
            'borrowed_at' => $now,
        ]);

        $book = $borrow->book;
        $book->decrement('stock');

        return back()->with('success', 'Peminjaman berhasil dikonfirmasi.');
    }

    public function return(Borrow $borrow)
    {
        $borrow->update(['status' => 'returned', 'return_date' => now()]);
        $book = $borrow->book;
        $book->increment('stock');
        return back()->with('success', 'Peminjaman selesai.');
    }

    public function overdue(Borrow $borrow)
    {
        $borrow->update(['status' => 'returned']);
        return back()->with('success', 'Peminjaman melewati batas pengembalian.');
    }

    public function archive(Borrow $borrow)
    {
        $borrow->update(['status' => 'archive']);
        return back()->with('success', 'Peminjaman berhasil diarsipkan.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $book = Book::find($request->book_id);
        if ($book->stock <= 0) {
            return redirect()->back()->with('error', 'Buku tidak tersedia untuk dipinjam.');
        }

        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
            'borrowed_at' => 'required|date',
        ]);

        $borrowedAt = Carbon::parse($validated['borrowed_at']);


        Borrow::create([
            'book_id' => $validated['book_id'],
            'user_id' => $validated['user_id'],
            'borrowed_at' => $borrowedAt,
            'due_date' => $borrowedAt->copy()->addDays(7),
            'status' => 'unconfirmed', // default status
        ]);

        return redirect()->route('borrows.index')->with('success', 'Peminjaman berhasil diajukan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Borrow $borrow)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Borrow $borrow)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Borrow $borrow)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrow $borrow)
    {
        $borrow->delete();
        return redirect()->route('borrows.index')->with('success', 'Peminjaman berhasil dihapus.');
    }
}
