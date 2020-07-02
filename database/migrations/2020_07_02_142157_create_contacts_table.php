<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('source_url','1024');
            $table->unsignedInteger('source_status')->nullable();
            $table->boolean('source_isCrawled');

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('title')->nullable();
            $table->string('location')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('company')->nullable();
            $table->string('company_website')->nullable();
            $table->string('company_industry')->nullable();
            $table->string('company_founded')->nullable();
            $table->string('company_size')->nullable();
            $table->string('company_linkedin')->nullable();
            $table->string('company_headquarters')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
