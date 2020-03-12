<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('id','<>', Auth::id())->get();
        return view('admin.users.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin.users.edit')->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // $validatedData = $request->validate([
        //     'name' => 'required',
        //     'email' => 'required|email:rfc,dns',
        //     'phone' => 'starts_with:011,012,010,015|digits:11',
        //     // 'role' => 'sometimes|required_without:active'
        // ]);
        if($request->name !== null) {
            $user->name = $request->name;
        }
        if($request->email !== null) {
            $user->email = $request->email;
        }
        if($request->phone !== null) {
            $user->phone = $request->phone;
        }
        elseif ($request->phone == null) {
            $user->phone = null;
        }
        if(isset($request->role)) {
            if($request->active == 0){
                return back()->with(['message' => "Cannot Promote an Inactive User to Admin"]);
            }
            else {
                $user->role = "admin"; //promote user
            }

        }
        elseif (!isset($request->role)) {
            $user->role = "user"; //degrade user
        }
        
        $user->save();
        return \redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return \redirect()->route('admin.users.index');
    }

    public function handleActiveStatus(Request $request, User $user)
    {
        if($user->active == 1){
            
            $user->active = 0;
        }
        else {
            $user->active = 1;
        }

        $user->save();
        return \redirect()->route('admin.users.index');
    }
}