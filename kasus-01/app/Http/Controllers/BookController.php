<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::latest()->get();

        return view('books.index', compact('books'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'published_year' => [
                'required',
                'integer',
                'digits:4',
                'min:1000',
                'max:' . now()->year,
            ],
        ], [
            'title.required' => 'Judul buku wajib diisi.',
            'author.required' => 'Nama penulis wajib diisi.',
            'published_year.required' => 'Tahun terbit wajib diisi.',
            'published_year.integer' => 'Tahun terbit harus berupa angka.',
            'published_year.digits' => 'Tahun terbit harus terdiri dari 4 angka.',
            'published_year.min' => 'Tahun terbit tidak boleh kurang dari 1000.',
            'published_year.max' => 'Tahun terbit tidak boleh melebihi tahun sekarang.',
        ]);

        Book::create($validated);

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil dihapus.');
    }
}
