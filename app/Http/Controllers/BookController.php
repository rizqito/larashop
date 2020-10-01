<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request, App\Book, Str, Auth;

class BookController extends Controller
{
    public function index(Request $request){
        $status = $request->get('status');
        $keyword = $request->get('keyword') ? $request->get('keyword') : '';
        if($status){
            $books = Book::with('categories')->where('title', "LIKE", "%".$keyword."%")->where('status', strtoupper($status))->paginate(10);
        } else {
            $books = Book::with('categories')->where("title", "LIKE", "%".$keyword."%")->paginate(10);
        }
        return view('books.index', compact('books');
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
        $book->categories()->attach($r->get('categories'));
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
        $book = Book::findOrFail($id);
        return view('books.edit',compact('book'));
    }

    public function update(Request $request, $id){
        $book = Book::findOrFail($id);
        $book->title = $request->get('title');
        $book->slug = $request->get('slug');
        $book->description = $request->get('description');
        $book->author = $request->get('author');
        $book->publisher = $request->get('publisher');
        $book->stock = $request->get('stock');
        $book->price = $request->get('price');
        $new_cover = $request->file('cover');
        if($new_cover){
            if($book->cover && file_exists(storage_path('app/public/' .$book->cover))){
                \Storage::delete('public/'. $book->cover);
            }
        }
        $book->updated_by = Auth::user()->id;
        $book->status = $request->get('status');
        $book->save();
        $book->categories()->sync($request->get('categories'));
        return redirect()->route('books.edit', [$book->id])->with('status','Book successfully updated');
    }

    public function destroy($id){
        $book = Book::findOrFail($id);
        $book->delete();
        return redirect()->route('books.index')->with('status', 'Book moved to trash');
    }

    public function trash(){
        $books = Book::onlyTrashed()->paginate(10);
        return view('books.trash', ['books' => $books]);
    }

    public function restore($id){
        $book = Book::withTrashed()->findOrFail($id);
        if($book->trashed()){
            $book->restore();
            return redirect()->route('books.trash')->with('status', 'Book successfully restored');
        } else {
            return redirect()->route('books.trash')->with('status', 'Book is not in trash');
        }
    }

    public function deletePermanent($id){
        $book = Book::withTrashed()->findOrFail($id);
        if(!$book->trashed()){
            return redirect()->route('books.trash')->with('status', 'Book is not in trash!')->with('status_type', 'alert');
        }else{
            $book->categories()->detach();
            $book->forceDelete();
            return redirect()->route('books.trash')->with('status', 'Book permanently deleted!');
        }
    }
}
