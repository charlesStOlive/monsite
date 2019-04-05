<?php namespace Charles\Mailgun\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateSegmentsTable extends Migration
{
    public function up()
    {
        Schema::create('charles_mailgun_segments', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->nullable();
            $table->timestamps();
        });

        Schema::create('charles_mailgun_contact_segment', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('contact_id')->unsigned();
            $table->integer('segment_id')->unsigned();
            $table->primary(['contact_id', 'segment_id'], 'contact_segment');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('charles_mailgun_segments');
        Schema::dropIfExists('charles_mailgun_contact_segment');
    }
}