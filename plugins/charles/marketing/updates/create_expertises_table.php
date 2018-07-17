<?php namespace Charles\Marketing\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateExpertisesTable extends Migration
{
    public function up()
    {
        Schema::create('charles_marketing_expertises', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('charles_marketing_expertises');
    }
}
