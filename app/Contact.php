<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
  protected $fillable = [
    // 'id',
    // 'created_at',
    // 'updated_at',
    'source_url',
    'source_status',
    'source_isCrawled',

    'first_name',
    'last_name',
    'email',
    'title',
    'location',
    'linkedin',
    'company',
    'company_website',
    'company_industry',
    'company_founded',
    'company_size',
    'company_linkedin',
    'company_headquarters',


  ];

}
