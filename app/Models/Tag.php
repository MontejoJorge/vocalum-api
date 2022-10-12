<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Tag extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [
      'id',
      'created_at',
      'updated_at'
    ];

    public function ads() {
      return $this->belongsToMany(Ad::class);
    }
}
