<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        // Nama parameter disamakan dengan name="" pada form: keyword & category
        $keyword  = $request->query('keyword');
        $category = $request->query('category');

        $books = Book::with('category')
            // Pencarian parsial (LIKE); kondisi judul OR penulis dibungkus
            // dalam satu grup supaya tidak merusak filter kategori (AND).
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('title', 'like', "%{$keyword}%")
                      ->orWhere('author', 'like', "%{$keyword}%");
                });
            })
            // Filter kategori hanya diterapkan jika dipilih
            ->when($category, function ($query) use ($category) {
                $query->where('category_id', $category);
            })
            ->orderBy('title')
            ->get();

        $categories = Category::orderBy('name')->get();

        // Nama variabel disamakan dengan yang dipakai Blade: $books
        return view('books.index', compact('books', 'categories', 'keyword', 'category'));
    }
}
