<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request, App\Category, Auth, Str, Storage, Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    public function __construct(){
        $this->middleware(function($request, $next){
            if(Gate::allows('manage-categories')) return $next($request);
            abort(403, 'Anda tidak memiliki cukup hak akses');
        });
    }

    public function index(Request $r){        
        $cat = Category::paginate(10);
        $filter = $r->get('name');
        if($filter){
            $cat = Category::where('name','LIKE','%$filter%')->paginate(10);
        }
        return view('categories.index',compact('cat'));
    }
    
    public function create(){
        return view('categories.create');
    }

    public function store(Request $r){
        Validator::make($r->all(), [
            "name" => "required|min:3|max:20",
            "image" => "required"
        ])->validate();

        $cat       = new Category;
        $cat->name = $r->get('name');
        $cat->slug = Str::slug($r->get('name'), '-');
        if($r->file('image')){
            $image_path = $r->file('image')->store('category_image','public');
            $cat->image = $image_path;
        }
        $cat->created_by = Auth::user()->id;        
        $cat->save();

        return redirect()->route('categories.create')->with('status','Category successfully created');
    }

    public function show($id){
        $cat = Category::findOrFail($id);

        return view('categories.show', compact('cat'));
    }

    public function edit($id){
        $cat = Category::findOrFail($id);

        return view('categories.edit',compact('cat'));
    }

    public function update(Request $r, $id){
        $cat       = Category::findOrFail($id);
        Validator::make($r->all(), [
            "name" => "required|min:3|max:20",
            "image" => "required",
            "slug" => [
                "required",
                Rule::unique("categories")->ignore($cat->slug, "slug")
            ]
        ])->validate();

        $name      = $r->get('name');
        $slug      = $r->get('slug');

        $cat->name = $name;
        $cat->slug = $slug;        
        if($r->file('image')){
            Storage::delete('public/'.$cat->image);
            $file       = $r->file('image')->store('category_image','public');
            $cat->image = $file;            
        }
        $cat->updated_by = Auth::user()->id;
        $cat->slug       = Str::slug($name);
        $cat->save();

        return redirect()->route('categories.edit', [$id])->with('status','Category successfully updated');
    }

    public function destroy($id){
        $cat = Category::findOrFail($id);
        $cat->delete();

        return redirect()->route('categories.index')->with('status','Category successfully moved to trash');
    }

    public function trash(){
        $cat = Category::onlyTrashed()->paginate(10);

        return view('categories.trash',compact('cat'));
    }

    public function restore($id){
        $cat = Category::withTrashed()->findOrFail($id);
        if($cat->trashed()){
            $cat->restore();
        }else{
            return redirect()->route('categories.index')->with('status','Category is not in trash');
        }

        return redirect()->route('categories.index')->with('status','Category successfully restored');
    }

    public function deletePermanent($id){
        $cat = Category::withTrashed()->findOrFail($id);
        if(!$cat->trashed()){
            return redirect()->route('categories.index')->with('status','Can not delete permanent active category');
        }else{
            $cat->forceDelete();
            return redirect()->route('categories.index')->with('status','Category permanently deleted');
        }
    }

    public function ajaxSearch(Request $r){
        $term = $r->q;
        $tags = Category::where('name','LIKE', '%'.$term.'%')->limit(5)->get();
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->name];
        }
        return \Response::json($formatted_tags);
    }
}