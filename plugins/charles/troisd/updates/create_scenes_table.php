<?php namespace Charles\Troisd\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateScenesTable extends Migration
{
    public function up()
    {
        Schema::create('charles_troisd_scenes', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->string('template_name');
            $table->text('environements')->nullable();
            $table->text('options')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('charles_troisd_scenes');
    }
}
