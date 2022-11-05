<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Util\JWT;
use App\Util\Validators\AuthValidator;

class UserController extends Controller
{
  public function getUser(Request $request) {

    $payload = JWT::decode($request->token);

    $user = User::where('email', $payload->email)->first();

    return response()->json($user, 200);
  }

  public function updateUser(Request $request) {

    $validation = AuthValidator::update($request->all());

    if ($validation->fails()) {
      return response()->json([
        'errors' => $validation->errors()
      ], 400);
    }

    $payload = JWT::decode($request->token);

    $user = User::where('email', $payload->email)->first();

    $user->name = $request->name;
    $user->surname = $request->surname;
    $user->phone = $request->phone;

    if ($user->google == 0) {
      $user->email = $request->email;
    }

    $user->save();
    return response()->json($user, 200);

  }

}
