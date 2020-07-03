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


    protected static $apikey = 'AIzaSyAc1SKyytc5h_1-qd0R-Emsa17iNQIIzZs';

    protected static $domain = 'beta.companieshouse.gov.uk';
    protected static $startUrl = 'https://beta.companieshouse.gov.uk';


    public function setUp(): void{
      parent::setUp();
      // $this->artisan('migrate:fresh');
    }

    /** @test */
    public function urlSpider()
    {

      $startingLink = Page::create([
        'url' => self::$startUrl,
        'isCrawled' => false,
      ]);

      $this->browse(function (Browser $browser) use ($startingLink) {
        $this->getLinks($browser, $startingLink);
      });
    }

    protected function getLinks(Browser $browser, $currentUrl){

      $this->processCurrentUrl($browser, $currentUrl);


      try{

        foreach(Page::where('isCrawled', false)->get() as $link) {
          $this->getLinks($browser, $link);
        }


      }catch(Exception $e){

      }
    }

    protected function processCurrentUrl(Browser $browser, $currentUrl){

      //Check if already crawled
      if(Page::where('url', $currentUrl->url)->first()->isCrawled == true)
      return;

      //Visit URL
      $browser->visit($currentUrl->url);

      //Get Links and Save to DB if Valid
      $linkElements = $browser->driver->findElements(WebDriverBy::tagName('a'));
      foreach($linkElements as $element){
        $href = $element->getAttribute('href');
        $href = $this->trimUrl($href);
        if($this->isValidUrl($href)){
          //var_dump($href);
          Page::create([
          'url' => $href,
          'isCrawled' => false,
          ]);
        }
      }

      //Update current url status to crawled
      $currentUrl->isCrawled = true;
      $currentUrl->status  = $this->getHttpStatus($currentUrl->url);
      $currentUrl->title = $browser->driver->getTitle();
      $currentUrl->save();
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
    // public function curl_get($endpoint,$userpwd)
    // {
    //
    //
    //   $ch = @curl_init();
    //   if (!empty($userpwd)) {
    //     curl_setopt($ch, CURLOPT_USERPWD, $userpwd['username'] . ":" . $userpwd['password']);
    //   }
    //   @curl_setopt($ch, CURLOPT_URL, $endpoint);
    //   @curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    //     'Accept: application/json',
    //     'Content-Type: application/json'
    //   ));
    //   @curl_setopt($ch, CURLOPT_HEADER, 0);
    //   @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //
    //   $response = @curl_exec($ch);
    //   $status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //   $curl_errors = curl_error($ch);
    //
    //   @curl_close($ch);
    //
    //
    //   $response = json_encode(json_decode($response, true),JSON_PRETTY_PRINT);
    //   return $response;
    //
    //
    // }


}
