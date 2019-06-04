<?php namespace Charles\Mailgun\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateCloudisTable extends Migration
{
    public function up()
    {
        Schema::create('charles_mailgun_cloudis', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->timestamps();
        });
        Schema::create('charles_mailgun_cloudi_client', function(Blueprint $table) {
            $table->integer('cloudi_id');
            $table->integer('client_id');
            $table->text('url')->nullable();
            $table->boolean('url_ready')->nullable();
            $table->primary(['cloudi_id', 'client_id']);
            });
        Schema::create('charles_mailgun_cloudi_contact', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('cloudi_id');
            $table->integer('contact_id');
            $table->text('url')->nullable();
            $table->boolean('url_ready')->nullable();
            $table->primary(['cloudi_id', 'contact_id']);
            });
    }



    public function down()
    {
        Schema::dropIfExists('charles_mailgun_cloudis');
        Schema::dropIfExists('charles_mailgun_cloudi_client');
        Schema::dropIfExists('charles_mailgun_cloudi_contact');
    }
}