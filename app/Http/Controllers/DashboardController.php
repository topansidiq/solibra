<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
}