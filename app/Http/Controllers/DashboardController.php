<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::all();
        $categories = Category::withCount('books')->get();
        $borrows = Borrow::all();
        $users = User::all();

        $books_count = Book::count();
        $categories_count = Category::count();
        $borrows_count = Borrow::count();
        $users_count = User::count();
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

        return view("admin.dashboard.index", compact("users", "borrows", "books", "categories", "latestBooks", "books_count", "categories_count", "borrows_count", "users_count", "selectedCategories"));
    }
}
