<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Util\JWT;
use App\Util\Validators\AuthValidator;

class AuthController extends Controller
{
  public function register(Request $request)
  {
    try {
      $validation = AuthValidator::register($request->all());

      if ($validation->fails()) {
        return response()->json([
          'errors' => $validation->errors()
        ], 400);
      }

      $user = User::create([
        'name' => $request->name,
        'surname' => $request->surname,
        'phone' => $request->phone,
        'email' => $request->email,
        'google' => False,
        'active' => True,
        'password' => Hash::make($request->password)
      ]);

      $jwt = JWT::encode(['email' => $user->email]);

      return response()->json([
        'token' => $jwt
      ], 200);

    } catch (\Throwable $th) {
      return response()->json([
        'message' => $th->getMessage()
      ], 500);
    }
  }

  public function loginUser(Request $request)
  {
    try {
      $validation = AuthValidator::login($request->all());

      if ($validation->fails()) {
        return response()->json([
          'errors' => $validation->errors()
        ], 400);
      }

      if (!Auth::attempt($request->only(['email', 'password'])) || $validation->fails()) {
        return response()->json([
          'errors' => ['login' => 'Email & Password does not match with our record.'],
        ], 401);
      }

      $user = User::where('email', $request->email)->first();

      $jwt = JWT::encode(['email' => $user->email]);

      return response()->json([
        'token' => $jwt
      ], 200);

    } catch (\Throwable $th) {
      return response()->json([
        'message' => $th->getMessage()
      ], 500);
    }
  }
}
