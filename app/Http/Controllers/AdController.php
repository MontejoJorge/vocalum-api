<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Ad;
use \App\Models\Tag;

class AdController extends Controller
{
  public function store(Request $request) {

    $user = User::where('email', $request->payload->email)->first();

    $imgUUID = Uuid::uuid4();
    $img = $request->file('photo');

    Storage::putFileAs('public', $img, $imgUUID . '.jpg');

    $ad = Ad::create([
      'title' => $request->title,
      'description' => $request->description,
      'price' => $request->price,
      'user_id' => $user->id,
      'photo' => $imgUUID
    ]);

    foreach ($request->tags as $tag) {

      $tag = strtolower($tag);
      $tag = str_replace(' ', '-', $tag);

      $tag = Tag::firstOrCreate(['name' => $tag]);
      
      $ad->tags()->attach($tag->id, ["id" => Uuid::uuid4()]);
    }

    $ad->tags = $ad->tags;

    return response()->json($ad, 200);
  }

  public function view(Request $request) {

    $ads = Ad::query();

    if ($request->search) {
      $ads->orWhere(function($query) use ($request) {
        $query->where('title', 'like', '%' . $request->search . '%')
          ->orWhere('description', 'like', '%' . $request->search . '%');
      });
    }

    if ($request->minPrice) {
      $ads->where('price', '>=', intval($request->minPrice));
    }

    if ($request->maxPrice) {
      $ads->where('price', '<=', intval($request->maxPrice));
    }

    if ($request->tags) {
      $ads->whereHas('tags', function($query) use ($request) {
        $query->whereIn('name', $request->tags);
      });
    }
    $ads = $ads->get();

    return response()->json([
      'count' => count($ads),
      'ads' => $ads], 200);
  }
}
