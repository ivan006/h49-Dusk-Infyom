<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegisterTest extends DuskTestCase
{
  use DatabaseMigrations;
    /**
     * A Dusk test example.
     * @test
     * @return void
     */
    public function a_user_can_register()
    {
        $this->browse(function (Browser $browser) {
          $browser->visit('/register')
          ->assertSee('Register')
          ->type('name', 'Ivan')
          ->type('email', 'ivan.copeland2015@gmail.com')
          ->type('password', 'secret123')
          ->type('password_confirmation', 'secret123')
          ->press('Register')
          ->assertPathIs('/home')
          ->waitForText('JS generated text')
          ->assertSee('JS generated text');
        });
    }
}
