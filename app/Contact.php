<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Page;

class Contact extends Model
{
  protected $fillable = [
      // 'id',
      // 'created_at',
      // 'updated_at',

      // 'source_url',
      // 'source_status',
      // 'source_isCrawled',

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


    protected static $current_tile = "-33.92,18.515";
    protected static $industry = "Software+company";

    protected static $apikey = 'AIzaSyAc1SKyytc5h_1-qd0R-Emsa17iNQIIzZs';
    protected static $domain = 'maps.googleapis.com';
    // protected static $startUrl = 'https://maps.googleapis.com/maps/api/place/textsearch/json?inputtype=textquery&key='.self::$apikey.'&query='.self::$industry.'&location='.self::$current_tile.'&radius=500';
    protected static $startUrl = 'https://maps.googleapis.com/maps/api/place/textsearch/json?inputtype=textquery&key='.'AIzaSyAc1SKyytc5h_1-qd0R-Emsa17iNQIIzZs'.'&query='."Software+company".'&location='."-33.92,18.515".'&radius=500';
    // protected static $startUrl = 'https://maps.googleapis.com/maps/api/place/textsearch/json?key=AIzaSyAc1SKyytc5h_1-qd0R-Emsa17iNQIIzZs&pagetoken=CrQCJwEAAF-DKOTOTg03B05Q8Tw72AsGV5y4dhrmtUHbHioe5ors-DMww1txRuq4PRz18xXvbqNjy-rirxxnKStAQzZZ5HJ4MV-FcyHSs4mcEyPtO6VA4_OYHfARfcNKGIIOQDmE4imzHQU6IHG-ZNuHqdH7PHTvsXcXOwqqPxAZG9SVO8hIm-vsnMgJy1alqei5Z_l_rcM8i_ORXIyrRj4WU3UAMGvt0M8h-mf4IjIxrcCVX8Ng6-R5gpV6bvXsonpMvu0A0H90IGSxyArutiXCE7YUvA2wt1qJ0Fk6zE129nGzV-Hgy1kkOuFjX_UFVRkRA41Lq3ZNKuXj3m9PU5LcypBmOG4GdY8XUB2t8ow-mjhirN7UHMRknR0LNMsJy4NIxZvoeHu7_q0WNG21hc0LFXSdo-ESEL6V5eeyjgxNR-CwX1LObmwaFHmIQwfI6vBNP033dGUgEsOc_91a';

    /** @test */
    public function urlSpider()
    {


      $startUrl = self::$startUrl;

      $startingPage = Page::create([
        'url' => $startUrl,
        'isCrawled' => false,
      ]);

      // $this->browse(function (Browser $browser) use ($startingLink) {
      //   $this->getLinks($browser, $startingLink);
      // });

      $this->getLinks($startingPage);
    }

    protected function getLinks($currentPage){

      $this->processCurrentUrl($currentPage);

      try{

        foreach(Page::where('isCrawled', false)->get() as $link) {
          $this->getLinks($link);
        }


      }catch(Exception $e){

      }
    }

    protected function processCurrentUrl($currentPage){

      //Check if already crawled
      if(Page::where('url', $currentPage->url)->first()->isCrawled == true)
      return;

      //Visit URL


      $contact_object = new Contact;
      $userpwd = array();
      sleep(1);
      $response = $contact_object->curl_get($currentPage->url,$userpwd);
      $response_json = $response;
      $response = json_decode($response, true);

      // echo $startUrl;
      // echo "<br>";
      // echo $response["next_page_token"];
      // dd($response);

      // $browser->visit($currentPage->url);

      //Get Links and Save to DB if Valid

      $has_next_page = $currentPage->url." ".$response_json;
      if (isset($response["next_page_token"])) {
        $href = 'https://maps.googleapis.com/maps/api/place/textsearch/json?key=AIzaSyAc1SKyytc5h_1-qd0R-Emsa17iNQIIzZs&pagetoken='.$response["next_page_token"];
        $href = $this->trimUrl($href);
        if($this->isValidUrl($href)){
          //var_dump($href);
          Page::create([
            'url' => $href,
            'isCrawled' => false,
          ]);
        }
        $has_next_page = "has next page";
      }

      //Update current url status to crawled
      $currentPage->isCrawled = true;
      $currentPage->status  = $this->getHttpStatus($currentPage->url);
      // $currentPage->title = $browser->driver->getTitle();
      $currentPage->title = $has_next_page;
      $currentPage->save();
      // if (1==1) {
      // }
      // if (1==1) {
      //   if (isset($response["results"])) {
      //     foreach ($response["results"] as $key => $value) {
      //       $contact_object->create([
      //         'company' => $value["name"],
      //         'company_industry' => false,
      //       ]);
      //     }
      //   }
      // }
    }


    protected function isValidUrl($url){
      $parsed_url = parse_url($url);

      if(isset($parsed_url['host'])){
        if(strpos($parsed_url['host'], self::$domain) !== false && !Page::where('url', $url)->exists()){
          return true;
        }
      }
      return false;
    }

    protected function trimUrl($url){
      $url = strtok($url, '#');
      $url = rtrim($url,"/");
      return $url;
    }

    protected function getHttpStatus($url){
      $headers = get_headers($url, 1);
      return intval(substr($headers[0], 9, 3));
    }




    // public function index(){
    //   // code...
    //   $contact_object = new Contact;
    //   $apikey = Self::$apikey;
    //
    //   $userpwd = array(
    //     // "username" => $apikey,
    //     // "password" => "X",
    //   );
    //   // $endpoint = 'https://maps.googleapis.com/maps/api/place/textsearch/json?key='.$apikey.'&query=restaurants+in+Sydney';
    //   $current_tile = "-33.92,18.515";
    //   $industry = "Software+company";
    //   $endpoint = 'https://maps.googleapis.com/maps/api/place/textsearch/json?inputtype=textquery&key='.$apikey.'&query='.$industry.'&location='.$current_tile.'&radius=500';
    //   echo $endpoint;
    //
    //   $response = $contact_object->curl_get($endpoint,$userpwd);
    //   $response = json_decode($response, true);
    //
    //   dd($response);
    //
    //   // return 123;
    // }
    //
    public function curl_get($endpoint,$userpwd)
    {


      $ch = @curl_init();
      if (!empty($userpwd)) {
        curl_setopt($ch, CURLOPT_USERPWD, $userpwd['username'] . ":" . $userpwd['password']);
      }
      @curl_setopt($ch, CURLOPT_URL, $endpoint);
      @curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept: application/json',
        'Content-Type: application/json'
      ));
      @curl_setopt($ch, CURLOPT_HEADER, 0);
      @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      $response = @curl_exec($ch);
      $status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
      $curl_errors = curl_error($ch);

      @curl_close($ch);


      $response = json_encode(json_decode($response, true),JSON_PRETTY_PRINT);
      return $response;


    }


}
