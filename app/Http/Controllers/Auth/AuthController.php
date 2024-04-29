<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Administrator;
use App\Models\Adminsitrator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\FlareClient\Http\Exceptions\InvalidData;

class AuthController extends Controller
{
    public function signup(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username|min:4|max:60',
            'password' => 'required|min:5|max:10'
        ]);
    
        if($validator->fails()){
            return response()->json([
                'status' => 'Invalid',
                'message' => $validator->errors()
            ], 422);
        }


        $userCreated = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password)
        ]);

        $user = User::where('username', $userCreated->username)->first();
        if($user && Hash::check($request->password, $user->password)){
            $token = $user->createToken(Str::random(100))->plainTextToken;
            return response()->json([
                'status' => 'Success',
                'token' => $token,
                'user' => $user
            ], 201);
        }

            return response()->json([
                'status' => 'Invalid'
            ]);

            
    }

    public function signin(Request $request){

        // Mencoba mengotentikasi dari tabel 'users'
        $validator = Validator::make($request->all(), [
            'username' => 'required|min:4|max:60',
            'password' => 'required|min:5|max:15'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'Invalid',
                'message' => 'Invalid fields'
            ]);
        }

        $user = User::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken(Str::random(100))->plainTextToken;
            return response()->json([
                'status' => 'Success',
                'token' => $token,
                'user' => $user,
            ]);
        }
        
        $admin = Administrator::where('username' , $request->username)->first();
            if ($admin &&  Hash::check($request->password, $admin->password)) {
                $token = $admin->createToken(Str::random(100))->plainTextToken;
                return response()->json([
                    'status' => 'Success',
                    'token' => $token,
                    'user' => $admin,
                ]);
            }

        return response()->json([
            'status' => 'Invalid',
            'message' => 'Wrong Username or Password'
        ], 401);
    }

    public function signout(){
        $user = Auth::guard('player')->check();
        if($user){
            Auth::guard('player')->user()->tokens()->delete();
            return response()->json([
                'status' => 'Success'
            ]);
        }
        $admin = Auth::guard('admin')->check();
        if($admin){
            Auth::guard('admin')->user()->tokens()->delete();
            return response()->json([
                'status' => 'Success'
            ]);
        }

        return response()->json(['status' => 'Success']);
    }
}