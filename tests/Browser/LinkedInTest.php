<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LinkedInTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     * @test
     * @return void
     */
    public function testExample()
    {
        // $this->browse(function (Browser $browser) {
        //     $browser->visit('/')
        //             ->assertSee('Laravel');
        // });
        $this->browse(function (Browser $browser) {
            $browser->call('POST', 'www.linkedin.com/oauth/v2/accessToken?grant_type=client_credentials&client_id=77s22rt1fld8ul&client_secret=hG7VVrVo3vTymPLI', [], [], [], [
              // 'Host' => '',
              // 'Content-Type' => 'application/x-www-form-urlencoded',
            ])
            ->assertSee('Laravel');
        });
    }
}
