<?php namespace Charles\Troisd\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateHpsTable extends Migration
{
    public function up()
    {
        Schema::create('charles_troisd_hps', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('mesh_id')->unsigned();
            $table->string('name');
            $table->string('slug');
            $table->boolean('is_ready')->default(0);
            $table->text('position')->nullable();
            $table->text('options')->nullable();
            $table->boolean('is_launch_mesh_animes');
            $table->text('launch_mesh_animes')->nullable();
            $table->boolean('is_launch_content');
            $table->boolean('is_filter_hps_tags');
            $table->string('type_btn');
            $table->string('type_interface');
            $table->string('content')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('charles_troisd_hps');
    }
}
