<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request, App\User, Validator;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{    
    public function __construct(){
        $this->middleware(function($request, $next){
            if(Gate::allows('manage-users')) return $next($request);
            abort(403, 'Anda tidak memiliki cukup hak akses');
        });
    }

    public function index(Request $r){
        $user          = User::paginate(10);
        $filterKeyword = $r->get('keyword');
        $status        = $r->get('status');
        if($filterKeyword){
            if($status){
                $user = User::where('email','LIKE','%$filterKeyword%')->where('status',$status)->paginate(10);
            }else{
                $user = User::where('email','LIKE','%$filterKeyword%')->paginate(10);
            }
        }
        return view('users.index',compact('user'));        
    }

    public function create(){
        return view('users.create');
    }

    public function store(Request $r){
        Validator::make($r->all(),[
            "name" => "required|min:5|max:100",
            "username" => "required|min:5|max:20",
            "roles" => "required",
            "phone" => "required|digits_between:10,12",
            "address" => "required|min:20|max:200",
            "avatar" => "required",
            "email" => "required|email",
            "password" => "required",
            "password_confirmation" => "required|same:password"
        ])->validate();

        $data = new User;
        $data->name     = $r->get('name');
        $data->username = $r->get('username');
        $data->roles    = json_encode($r->get('roles'));
        $data->name     = $r->get('name');
        $data->address  = $r->get('address');
        $data->phone    = $r->get('phone');
        $data->email    = $r->get('email');
        $data->password = \Hash::make($r->get('password'));
        if($r->file('avatar')){
            $file         = $r->file('avatar')->store('avatars','public');
            $data->avatar = $file;
        }
        $data->save();

        return redirect()->route('users.create')->with('status','user successfully created.');
    }

    public function show($id){
        $user = User::findOrFail($id);
        return view('users.show',compact('user'));
    }
    
    public function edit($id){
        $user = User::findOrFail($id);
        return view('users.edit',compact('user'));
    }

    public function update(Request $r, $id){
        Validator::make($r->all(), [
            "name" => "required|min:5|max:100",
            "roles" => "required",
            "phone" => "required|digits_between:10,12",
            "address" => "required|min:20|max:200",
        ])->validate();
            
        $user          = User::findOrFail($id);
        $user->name    = $r->get('name');
        $user->roles   = json_encode($r->get('roles'));
        $user->address = $r->get('address');
        $user->phone   = $r->get('phone');
        $user->status  = $r->get('status');
        if($r->file('avatar')){
            \Storage::delete('public/'.$user->avatar);
            $file         = $r->file('avatar')->store('avatars','public');
            $user->avatar = $file;            
        }
        $user->save();        
        return redirect()->route('users.edit', [$id])->with('status','User successfully updated');
    }

    public function destroy($id){
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('status','User successfully deleted');
    }
}
