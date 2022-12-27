<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Validator;
use DB;
use Session;

class RegisterController extends Controller
{
    public function create()
    {
        //$roles = Role::all();
        $roles = DB::table('roles')->where('name', '!=' ,'superadmin')->get();

        //return view('register.create');
        return view('pages.user.add-user')->with('roles', $roles);
    }

    public function store(Request $request){

        $attributes = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:5|max:255',
            'phone' => '',
            'location' => 'max:255',
            'about' => 'max:255',
            'role' => 'string|in:admin,user'
        ]);

        // $validator = Validator::make($request->all(),[
        //     'name' => 'required|max:255',
        //     'email' => 'required|email|max:255|unique:users,email',
        //     'password' => 'required|min:5|max:255',
        //     'phone' => 'min:11|max:255',
        //     'location' => 'max:255',
        //     'about' => 'max:255',
        //     'role' => 'string|in:admin,user'
            
        //     ]);
        
        $user = User::create($attributes)->assignRole($request->role);
        if($user){
            Session::flash('alert-success', 'User created successfully!'); 
        }
        else{
            Session::flash('alert-warning', 'An error occurred while creating this user!'); 
        }
        
        //auth()->login($user);
        
        return redirect('/user-management');
    } 
}
