<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;
use Session;

class UsersController extends Controller
{
    //
    public function __construct(){

    }

    public function index(){
        //$roles = DB::table('roles')->where('name', '!=' ,'superadmin')->get();
       // $users = User::all();
        $users = User::all()->reject(function ($user) {
            return in_array("superadmin", $user->getRoleNames()->toArray()) == true;
        })->map(function ($user) {
            return $user;
        });
        //dd($users);
        //return view('register.create');
        return view('pages.user.user-management')->with('users', $users);
    }

    public function showUser(Request $request){
        //$roles = DB::table('roles')->where('name', '!=' ,'superadmin')->get();
        $user = auth()->user();
        if($user->can('edit user')){
            $roles = Role::where('name','!=','superadmin')->get();
            $id = $request->route('userid');
            $user = User::where('id','=',$id)->first();
            if($user){
                return view('pages.user.edit-user')->with('user', $user)->with('roles', $roles);
            }
            else{
                Session::flash('alert-warning', 'No user found with this id!'); 
                return redirect('/user-management');
            }
        }
        else{
            
            Session::flash('alert-warning', 'You are not allowed to perform this operation!'); 
            return redirect('/user-management');
        }

        //return view('register.create');
        
    }

    public function editUser(Request $request){
        $user = auth()->user();
        if($user->can('edit user')){
            $id = $request->route('userid');
            $user = User::where('id',$id);

            return view('pages.user.edit-user')->with('user', $user);
        }
        else{
            Session::flash('alert-warning', 'You are not allowed to perform this operation!'); 
            return redirect('/user-management');
        }
    }

    public function deleteUser(Request $request){
        //$roles = DB::table('roles')->where('name', '!=' ,'superadmin')->get();
        $id = $request->route('userid');
        try{
            $deleted = User::where('id',$id)->first()->delete();
            if($deleted){
                Session::flash('alert-success', 'User deleted Successfully!'); 
            }
            else{
                Session::flash('alert-warning', 'An error occured while deleting this user!'); 
            }
        }
        catch(Exception $e){
            Session::flash('alert-warning', 'An error occured while deleting this user!'); 
        }

        //return view('register.create');
        return redirect('/user-management');
    }
}
