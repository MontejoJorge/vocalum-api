<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\ExpiredException;
use App\Util\JWT;

class Auth
{
  public function handle(Request $request, Closure $next) {

    try {

      $token = strval($request->header('x-auth-token'));
      $request->token = $token;

      if (!$token) {
        return response()->json([
          'message' => 'Token is required.'
        ], 401);
      }

      $payload = JWT::decode($token);
      return $next($request);

    } catch (SignatureInvalidException) {
      return response()->json([
        'message' => 'Invalid token.'
      ], 401);
    } catch (ExpiredException) {
      return response()->json([
        'message' => 'Expired token.'
      ], 401);
    }
    
  }
}
