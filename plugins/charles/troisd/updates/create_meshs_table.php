<?php namespace Charles\Troisd\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateMeshsTable extends Migration
{
    public function up()
    {
        Schema::create('charles_troisd_meshs', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('scene_id')->unsigned();
            $table->string('name');
            $table->string('slug');
            $table->string('url')->nullable();
            $table->text('position')->nullable();
            $table->text('rotation')->nullable();
            $table->text('scale')->nullable();
            $table->text('options')->nullable();
            $table->boolean('replace_tex')->default(0);
            $table->text('new_tex')->nullable();
            $table->boolean('has_instances')->nullable();
            $table->string('instance_position_from')->nullable();
            $table->boolean('has_mesh_animes')->nullable();
            $table->text('mesh_animes')->nullable();
            $table->boolean('has_hps')->nullable();
            $table->boolean('override_hps_position')->nullable();
            $table->string('hps_position_from')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('charles_troisd_meshs');
    }
}
