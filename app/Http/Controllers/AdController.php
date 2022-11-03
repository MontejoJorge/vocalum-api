<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Ad;
use \App\Models\Tag;
use App\Util\Validators\AdValidator;

class AdController extends Controller
{
  public function store(Request $request) {

    $validation = AdValidator::store($request->all());

    if ($validation->fails()) {
      return response()->json([
        'errors' => $validation->errors()
      ], 400);
    }

    $user = User::where('email', $request->payload->email)->first();

    $img = $request->file('photo');
    $imgName = Uuid::uuid4() . '.'.$img->getClientOriginalExtension();

    Storage::disk('s3')->putFileAs('/', $img, $imgName);

    $url = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', trim($request->title, '-'))) . '-' . explode('-', Uuid::uuid4())[4];

    $ad = Ad::create([
      'title' => $request->title,
      'description' => $request->description,
      'price' => $request->price,
      'user_id' => $user->id,
      'photo' => $imgName,
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

    $validation = AdValidator::view($request->all());

    if ($validation->fails()) {
      return response()->json([
        'errors' => $validation->errors()
      ], 400);
    }

    $ads = Ad::query();

    if ($request->search) {
      $ads->orWhere(function($query) use ($request) {
        $query->where('title', 'like', '%' . $request->search . '%')
          ->orWhere('description', 'like', '%' . $request->search . '%');
      });
    }

    if ($request->user_email) {
      $user = User::where('email', $request->user_email)->first();
      $ads->where('user_id', '=', $user->id);
    } else if ($request->payload && $request->payload->email) {
      $user = User::where('email', $request->payload->email)->first();
      $ads->where('user_id', '!=', $user->id);
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

    $ads = $ads->with('user');

    $res = $ads->paginate(25);

    return response()->json($res, 200);
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
    Storage::disk('s3')->delete($ad->photo);

    return response()->json(['message' => 'Ad deleted'], 200);
  }

}
