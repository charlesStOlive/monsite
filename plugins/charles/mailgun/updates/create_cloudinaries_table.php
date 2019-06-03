<?php namespace Charles\Mailgun\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateCloudinariesTable extends Migration
{
    public function up()
    {
        Schema::create('charles_mailgun_cloudis', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->nullable();
            $table->timestamps();
        });
        Schema::create('charles_mailgun_cloudables', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('cloudi_id');
            $table->integer('cloudable_id');
            $table->string('cloudable_type')->nullable();
            $table->text('url')->nullable();
            });
    }



    public function down()
    {
        Schema::dropIfExists('charles_mailgun_cloudinaries');
    }
}
