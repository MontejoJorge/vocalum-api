<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\ExpiredException;
use App\Util\JWT;

class Auth
{
  public function handle(Request $request, Closure $next, $needed = true) {

    $needed = filter_var($needed, FILTER_VALIDATE_BOOLEAN);

    try {

      $token = strval($request->header('x-auth-token'));
      $request->token = $token;

      if (!$token && $needed) {
        return response()->json([
          'message' => 'Token is required.'
        ], 401);
      }

      $payload = JWT::decode($token);
      $request->payload = $payload;
      return $next($request);

    } catch (\Exception) {
      $request->token = null;
      if ($needed) {
        return response()->json([
          'message' => 'Invalid token.'
        ], 401);
      } else {
        return $next($request);
      }
    }
    
  }
}
