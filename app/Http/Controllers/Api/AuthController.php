<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Util\JWT;

class AuthController extends Controller
{
  public function register(Request $request)
  {
    try {
      $validateUser = Validator::make(
        $request->all(),
        [
          'name' => 'required',
          'email' => 'required|email|unique:users,email',
          'password' => 'required'
        ]
      );

      if ($validateUser->fails()) {
        return response()->json([
          'status' => false,
          'message' => 'validation error',
          'errors' => $validateUser->errors()
        ], 401);
      }

      $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password)
      ]);

      $payload = ['email' => $user->email];

      $jwt = JWT::encode($payload);

      return response()->json([
        'status' => true,
        'message' => 'User Created Successfully',
        'token' => $jwt
      ], 200);
    } catch (\Throwable $th) {
      return response()->json([
        'status' => false,
        'message' => $th->getMessage()
      ], 500);
    }
  }

  public function loginUser(Request $request)
  {
    try {
      $validateUser = Validator::make(
        $request->all(),
        [
          'email' => 'required|email',
          'password' => 'required'
        ]
      );

      if ($validateUser->fails()) {
        return response()->json([
          'status' => false,
          'message' => 'validation error',
          'errors' => $validateUser->errors()
        ], 401);
      }

      if (!Auth::attempt($request->only(['email', 'password']))) {
        return response()->json([
          'status' => false,
          'message' => 'Email & Password does not match with our record.',
        ], 401);
      }

      $user = User::where('email', $request->email)->first();

      $payload = ['email' => $user->email];

      $jwt = JWT::encode($payload);

      return response()->json([
        'status' => true,
        'message' => 'User Logged In Successfully',
        'token' => $jwt
      ], 200);

    } catch (\Throwable $th) {
      return response()->json([
        'status' => false,
        'message' => $th->getMessage()
      ], 500);
    }
  }
}
