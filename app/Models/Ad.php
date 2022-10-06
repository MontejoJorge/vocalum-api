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
      'updated_a'
    ];
}
