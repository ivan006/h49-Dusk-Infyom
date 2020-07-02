<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Contact;

class ContactController extends Controller
{
  public function index(){
    $contact_object = new Contact;
    $result = $contact_object->index();
    return $result;

  }
}
