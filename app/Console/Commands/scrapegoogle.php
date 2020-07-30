<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Contact;

class scrapegoogle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrapegoogle:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'scrapegoogle:cron';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $contact_object = new Contact;
      $contact_object->urlSpider();
    }
}
