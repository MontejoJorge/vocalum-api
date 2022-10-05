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

      $payload = JWT::decode($request->token);
      return $next($request);

    } catch (SignatureInvalidException) {
      return response(null, 401);
    } catch (ExpiredException) {
      return response(null, 401);
    }
    
  }
}
