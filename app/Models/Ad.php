<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Ad extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [
      'id',
      'users_id',
      'created_at',
      'updated_at'
    ];

    protected $hidden = [
      'id',
      'user_id'
    ];

    public function user() {
      return $this->belongsTo(User::class);
    }

    public function tags() {
      return $this->belongsToMany(Tag::class, 'ads_tags');
    }
}
