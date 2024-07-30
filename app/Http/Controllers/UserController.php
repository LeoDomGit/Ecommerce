<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{

    public function register(){
        return Inertia::render('Users/Register');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function account()
    {
        $user = Auth::user();
        return Inertia::render('Users/Account',['data'=>$user]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function checkLogin(Request $request){
        $validator = Validator::make($request->all(), [
            'email'=>'required|exists:users,email',
            'password'=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['check' => false, 'msg' => $validator->errors()->first()]);
        }
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password],1)){
            return response()->json(['check'=>true]);
        }else{
            return response()->json(['check'=>false,'msg'=>'Login Failed']);
        }
    }
 /**
     * Store a newly created resource in storage.
     */
    public function login(){
        return Inertia::render('Users/Login');

    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email'=>'required|unique:users,email',
            'password'=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['check' => false, 'msg' => $validator->errors()->first()]);
        }
        $data = $request->all();
        $data['password']= Hash::make($request->password);
        $data['created_at']= now();
        $user = User::firstOrCreate($data);
        return response()->json(['check'=>true]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
