<?php

namespace App\Util\Validators;

use Illuminate\Support\Facades\Validator;

class AuthValidator { 

  public static function register($request) {
    return Validator::make($request, [
      'name' => 'required|max:255',
      'surname' => 'required|max:255',
      'phone' => 'required|size:9|regex:/^[679]{1}[0-9]{8}$/',
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