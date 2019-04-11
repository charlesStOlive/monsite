<?php namespace Charles\Marketing\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateMoasTable extends Migration
{
    public function up()
    {
        Schema::create('charles_marketing_moas', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->string('accroche')->nullable();
            $table->integer('sort_order')->nullable();
            $table->timestamps();
        });

        Schema::create('charles_marketing_competence_moa', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('moa_id')->unsigned();
            $table->integer('competence_id')->unsigned();
            $table->primary(['competence_id' , 'moa_id' ], 'competence_moa');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('charles_marketing_moas');
        Schema::dropIfExists('charles_marketing_competence_moa');
    }
}
