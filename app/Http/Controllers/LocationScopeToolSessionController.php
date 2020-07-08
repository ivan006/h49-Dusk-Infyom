<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\LocationScopeToolSession;

class ContactController extends Controller
{
  public function index(){
    $location_scope_tool_session_object = new LocationScopeToolSession;
    $result = $location_scope_tool_session_object->index();
    return $result;

  }
}
