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

    $fileSystem = Storage::disk('sftp');
    $fileSystem->putFileAs('/', $img, $imgUUID.'.'.$img->getClientOriginalExtension());

    $url = strtolower(str_replace(' ', '-', $request->title)) . '-' . explode('-', Uuid::uuid4())[4];

    $ad = Ad::create([
      'title' => $request->title,
      'description' => $request->description,
      'price' => $request->price,
      'user_id' => $user->id,
      'photo' => $imgUUID,
      'url' => $url
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

    //if the request payload is not null and have an email then we will return the ads that not belong to that user
    // if ($request->payload && $request->payload->email) {
    //   $user = User::where('email', $request->payload->email)->first();
    //   $ads->where('user_id', '!=', $user->id);
    // }

    if ($request->user_email) {
      $user = User::where('email', $request->user_email)->first();
      $ads->where('user_id', '=', $user->id);
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

    if (in_array($request->orderByPrice, ['asc', 'desc'])) {
      $ads->orderBy('price', $request->orderByPrice);
    }

    $ads = $ads->with('tags');

    //return the user of each ad
    $ads = $ads->with('user');

    $ads = $ads->get();

    return response()->json([
      'count' => count($ads),
      'ads' => $ads], 200);
  }

  public function viewOne(Request $request) {

    $ad = Ad::where('url', $request->url)->first();

    if (!$ad) {
      return response()->json(['error' => 'Ad not found'], 404);
    }

    $ad->tags = $ad->tags;
    
    $ad->user = $ad->user;

    return response()->json($ad, 200);
  }

  public function delete(Request $request) {

    $ad = Ad::where('url', $request->url)->first();

    if (!$ad) {
      return response()->json(['error' => 'Ad not found'], 404);
    }

    if ($ad->user->email !== $request->payload->email) {
      return response()->json(['error' => 'You are not allowed to delete this ad'], 403);
    }

    $ad->delete();

    return response()->json(['message' => 'Ad deleted'], 200);
  }
}
