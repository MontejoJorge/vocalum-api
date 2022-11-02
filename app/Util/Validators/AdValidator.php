<?php

namespace App\Util\Validators;

use Illuminate\Support\Facades\Validator;

class AdValidator { 

  public static function store($request) {
    return Validator::make($request, [
      'title' => 'required|max:50',
      'description' => 'required|max:500',
      'price' => 'required|numeric|min:0|max:9999999',
      'photo' => 'required|image|mimes:jpg,png,jpeg|max:4096',
    ], [
      'photo.max' => 'The photo size must be less than 4MB.'
    ]);
  }

  public static function view($request) {
    return Validator::make($request, [
      'search' => 'max:500',
      'minPrice' => 'numeric|min:0|max:9999999',
      'maxPrice' => 'numeric|min:0|max:9999999',
      'minPrice' => 'lte:maxPrice',
      'maxPrice' => 'gte:minPrice',
      'tags' => 'image|mimes:jpg,png,jpeg|max:4096',
      'orderByPrice' => 'in:asc,desc,no',
    ],[
      'minPrice.lte' => 'The minimum price must be less than the maximum price.',
      'maxPrice.gte' => 'The maximum price must be greater than the minimum price.',
    ]);
  }

}