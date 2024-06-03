<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();
        if($user->count() > 0)
        return response()->json([
            "data" => $user
        ],200);
        else{
            return response()->json([
                "message" => "no user found"
            ],200);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "string|max:20|min:2|required",
            "username" => "string|max:16|min:4|required|unique:users,username",
            "email" => "string|max:50|min:1|required|unique:users,email",
            "password" => "string|max:16|min:4|required",
        ]);



       try {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            "message" => "User Created successfully",
        ]);
       }
       catch(ErrorException $e) {
        return response()->json([
            "message" => $e->getMessage()
        ]);
       }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'data' => $user
        ]);
    }


    public function update(UserUpdateRequest $request)
    {

        try{

            $user = $request->user();
            if($request->has("name")) {

                $user->name = $request->name;
            }
            if($request->has("username")) {

                $user->username = $request->username;
            }
            if($request->has("email")) {

                $user->email = $request->email;
            }

            if($request->has("password")) {

                $user->password = Hash::make($request->password);
            }


            $user->save();

            return response()->json([
                "message" => "user updated successfully",
                "user" => $user

            ]);

        }catch(ErrorException $e) {
            return response()->json([
                "message" => $e->getMessage()
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {

            $user = User::findOrFail($user);


            $user->delete();
            return response()->json([
                "message" => "user deleted successfully"
            ]);

        }catch(ErrorException $e){


            return response()->json([
                "message" => $e->getMessage()
            ]);

        }
    }
    public function login(Request $request) {
        $request->validate([
            "email" => "email|required",
            "password" => "required|string"
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user || ! Hash::check($request->password, $user->password)){
            return response()->json([
                "message" => "invalid credentials"
            ]);
        }



        return response()->json([
            "message" => "login successfull",
            "token" => $user->createToken("token")->plainTextToken
        ]);
    }
    public function logout (Request $request) {
        try{
            $user = $request->user();
            $user->currentAccessToken()->delete();
            return response()->json([
                "message" => "logged out successfully"
            ],200);
        }
        catch(ErrorException $e){
            return response()->json([
                "message" => $e->getMessage()
            ]);
        }
    }
}
