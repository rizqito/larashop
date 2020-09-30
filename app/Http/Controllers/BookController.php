<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request, App\Book, Str, Auth;

class BookController extends Controller
{
    public function index(){
        //
    }

    public function create(){
        return view('books.create');
    }

    public function store(Request $r){
        $book = new Book;
        $book->title = $r->get('title');
        $book->description = $r->get('description');
        $book->author = $r->get('author');
        $book->publisher = $r->get('publisher');
        $book->price = $r->get('price');
        $book->stock = $r->get('stock');
        $book->status = $r->get('save_action');
        $cover = $r->file('cover');
        if($cover){
            $cover_path = $cover->store('book-cover','public');
            $book->cover = $cover_path;
        }
        $book->slug = Str::slug($r->get('title'));
        $book->created_by = Auth::user()->id;
        $book->save();
        $new_book->categories()->attach($r->get('categories'));
        if($r->get('save_action') == 'PUBLISH'){
            return redirect()->route('books.create')->with('status','Book successfully saved and published');
        }else{
            return redirect()->route('books.create')->with('status','Book saved as draft');
        }
    }

    public function show($id){
        //
    }

    public function edit($id){
        //
    }

    public function update(Request $request, $id){
        //
    }

    public function destroy($id){
        //
    }
}
