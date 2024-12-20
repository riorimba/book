<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index() {
        $books = Book::paginate();

        return response()->json($books);
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

        return response()->json(['success' => 'Book created successfully']);
    }

    public function show(Book $book){
        return response()->json($book);
    }

    public function update(Request $request, Book $book)
    {
        $validatedData = $request->validate([
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'name' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'is_published' => ['required', 'boolean'],
        ]);

        if ($request->hasFile('cover_image')) {
            // delete old image
            Storage::delete('images/' . $book->cover_image);

            // store new image in public folder
            $fileName = time() . '.' . $request->file('cover_image')->getClientOriginalExtension();
            $validatedData['cover_image'] = $request->file('cover_image')->move(public_path('images'), $fileName);
            $validatedData['cover_image'] = 'images/' . $fileName;
        }

        $book->update($validatedData);

        return response()->json(['success' => 'Book updated successfully']);
    }

    public function destroy(Book $book){
        // delete image
        Storage::delete('images/' . $book->cover_image);

        $book->delete();

        return response()->json(['success' => 'Book deleted successfully']);
    }
}
