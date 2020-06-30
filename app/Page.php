<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
  protected $fillable = [
    // 'id',
    'url',
    'title',
    'status',
    'isCrawled',
    // 'created_at',
    // 'updated_at',
  ];
}
