<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Book::class;
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'author' => $this->faker->name(),
            'publisher' => $this->faker->company(),
            'year' => $this->faker->year(),
            'isbn' => $this->faker->unique()->isbn13(),
            'stock' => $this->faker->numberBetween(1, 100),
            'description' => $this->faker->paragraph(),
            'cover' => 'covers/default.jpg', // sesuaikan path jika pakai gambar
        ];
    }
}