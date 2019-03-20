<?php namespace Charles\Marketing\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateMissionsAndTargetsTables extends Migration
{
    public function up()
    {
        Schema::create('charles_marketing_missions', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->integer('sort_order')->nullable();
            $table->timestamps();
        });

        Schema::create('charles_marketing_targets', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->integer('sort_order')->nullable();
            $table->timestamps();
        });

        Schema::create('charles_marketing_mission_target', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('mission_id')->unsigned();
            $table->integer('target_id')->unsigned();
            $table->primary(['mission_id', 'target_id' ], 'mission_target');
            $table->timestamps();
        });

        Schema::create('charles_marketing_competence_mission', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('mission_id')->unsigned();
            $table->integer('competence_id')->unsigned();
            $table->primary(['competence_id' , 'mission_id' ], 'competence_mission');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('charles_marketing_targets');
        Schema::dropIfExists('charles_marketing_missions');
        Schema::dropIfExists('charles_marketing_competence_mission');
        Schema::dropIfExists('charles_marketing_mission_target');
    }
}