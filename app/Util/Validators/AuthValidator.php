<?php

namespace App\Util\Validators;

use Illuminate\Support\Facades\Validator;

class AuthValidator { 

  public static function register($request) {
    return Validator::make($request, [
      'name' => 'required',
      'email' => 'required|email|unique:users,email',
      'password' => 'required'
    ]);
  }

  public static function login($request) {
    return Validator::make($request, [
      'email' => 'required|email',
      'password' => 'required'
    ]);
  }

}