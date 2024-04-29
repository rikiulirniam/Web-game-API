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
            ]);
        }

        $user = User::create($validator->validated());


        if(Auth::guard('user')->attempt($validator->validated())){
            $user = Auth::guard('user')->user();
            $user->accessToken = $user->createToken(Str::random(100))->plainTextToken;
            return response()->json([
                'status' => 'Success',
                'user' => $user
            ]);
        }

        


    }
    public function signin(Request $request){

    // Mencoba mengotentikasi dari tabel 'users'
    $validator = Validator::make($request->all(), [
        'username' => 'required|min:4|max:60',
        'password' => 'required|min:5|max:10'
    ]);

    if($validator->fails()){
        return response()->json([
            'status' => 'Invalid',
            'message' => $validator->errors()
        ]);
    }
    if (Auth::guard('user')->attempt(['username' => $request->username, 'password' => $request->password])) {
       return response()->json(['Login sebagai user', 'user' => Auth::guard('user')->user()]);
    }
    
    if (!Auth::check()) {
        if (Auth::guard('admin')->attempt(['username' => $request->username, 'password' => $request->password])) {
                return response()->json(['Login sebagai admin', 'user' => Auth::guard('admin')->user()]);
            }
        }
    }

}
