<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::factory()->count(20)->create();

        // Buat 20 buku dan assign kategori random
        Book::factory()->count(300)->create()->each(function ($book) use ($categories) {
            $book->categories()->attach(
                $categories->random(rand(1, 4))->pluck('id')->toArray()
            );
        });
    }
}
