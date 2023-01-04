<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    /**
     * Display a listing of the prducts.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }


     /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function create()
    {
        $roles = Role::orderBy('name', 'DESC')->get()->pluck('name', 'id')->toArray();
        return view('users.create', compact('roles'));
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
        ]);
        
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ]);
        $user->assignRole([$request->get('role')]);

        return redirect()->route('users')->with('message', 'user created!'); 
    }
  
    /**
     * Show the step One Form for creating a new product.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::where('id', $id)->first();
        return view('users.edit', compact('user'));
    }

    /**  
     * Post Request to store step1 info in session
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required'
        ]);

        $dataToUpdate = [
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => !empty($request->get('password')) ? bcrypt($request->get('password')) : ''
        ];
        User::where('id', $id)->update(array_filter($dataToUpdate));

        return redirect()->route('users');
    }

    public function delete($id)
    {
        User::where('id', $id)->delete();
        return redirect()->route('users')->with('message', 'user delete!');
    }
    
}
