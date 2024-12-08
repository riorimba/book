<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(){
        $books = Book::paginate(10);

        return view('books.index', compact('books'));
    }

    public function create(){
        return view('books.create');
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'cover_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'name' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'is_published' => ['required', 'boolean'],
        ]);

        if ($request->hasFile('cover_image')) {
            // store new image in public folder
            $fileName = time() . '.' . $request->file('cover_image')->getClientOriginalExtension();
            $request->file('cover_image')->move(public_path('images'), $fileName);
            $validatedData['cover_image'] = 'images/' . $fileName;
        }
        Book::create($validatedData);

        return to_route('books.index')->with('success', 'Book created successfully');
    }

    public function show($id)
    {
        $book = Book::find($id);

        return view('books.show', compact('book'));
    }

    public function edit($id)
    {
        $book = Book::find($id);

        return view('books.edit', compact('book'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'name' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'is_published' => ['required', 'boolean'],
        ]);

        $book = Book::find($id);

        if ($request->hasFile('cover_image')) {
            // delete old image
            Storage::delete('public/' . $book->cover_image);

            // store new image in public folder
            $fileName = time() . '.' . $request->file('cover_image')->getClientOriginalExtension();
            $validatedData['cover_image'] = $request->file('cover_image')->move(public_path('images'), $fileName);
            $validatedData['cover_image'] = 'images/' . $fileName;
        }

        $book->update($validatedData);

        return to_route('books.index')->with('success', 'Book updated successfully');
    }
}
