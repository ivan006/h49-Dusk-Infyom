<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

use App\Page;
use Facebook\WebDriver\WebDriverBy;

class duskCrawlerTest extends DuskTestCase
{
    // /**
    //  * A Dusk test example.
    //  *
    //  * @return void
    //  */
    // public function testExample()
    // {
    //     $this->browse(function (Browser $browser) {
    //         $browser->visit('/')
    //                 ->assertSee('Laravel');
    //     });
    // }

    protected static $domain = 'hangar49.com';
    protected static $startUrl = 'https://hangar49.com/';


    public function setUp(): void{
        parent::setUp();
        $this->artisan('migrate:fresh');
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
}
