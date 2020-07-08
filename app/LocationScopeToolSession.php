<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocationScopeToolSession extends Model
{
  public function index()
  {

        return view('welcome', compact('result','title'));
  }
}
