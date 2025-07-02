<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('books')->get();
        return view("admin.categories.index", compact("categories"));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => "string|required|max:100|unique:categories,name"
            ]);

            $category = Category::create($validated);

            return redirect()->route('categories.index')->with('succes', 'Kategori "' . $category->name . '" berhasil ditambahkan.');

            // return view("admin.categories.index", compact("category"));
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangani error validasi
            Log::error('Validation Error when storing category: ' . $e->getMessage(), ['errors' => $e->errors()]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput(); // Keep old input in the form
        } catch (\Exception $e) {
            // Tangani error umum lainnya
            Log::error('Error storing category: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan kategori. Silakan coba lagi.')
                ->withInput();
        }
    }
}
