<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Util\JWT;

class UserController extends Controller
{
  public function getUser(Request $request) {

    $payload = JWT::decode($request->token);

    $user = User::where('email', $payload->email)->first();

    return response()->json($user, 200);
  }
}
