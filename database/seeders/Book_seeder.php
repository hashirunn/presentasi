<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Book_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::create([
            'title' => 'Filosofi Jarkom',
            'author' => 'TGA',
            'year' => 2024,
            'publisher' => 'UNSUR Mengantuk',
            'city' => 'Cianjir',
            'cover' => 'public/cover.jgp',
            'bookshelf_id' => 2
        ]);
    }
}
