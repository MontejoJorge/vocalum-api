<?php

namespace App\Util;

use Firebase\JWT\JWT as JsonWT;
use Firebase\JWT\Key;

class JWT {

  public static function encode(Array $payload) {

    $exp_date = date("Y-m-d H:i:s", strtotime('+8 hours'));
    $payload['exp'] = strtotime($exp_date);

    return JsonWT::encode($payload, env('APP_KEY'), 'HS256');
  }

  public static function decode($jwt) {
    return JsonWT::decode($jwt, new Key(env('APP_KEY'), 'HS256'));
  }
}