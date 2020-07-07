<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
  protected $fillable = [
    'status_flag',
    'status_note',
  ];

}
