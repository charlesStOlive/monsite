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
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('charles_marketing_expertises_project', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('project_id')->unsigned();
            $table->integer('expertise_id')->unsigned()
            $table->primary(['project_id', 'expertise_id'], 'project_provision');
            $table->timestamps();
        });


    }

    public function down()
    {
        Schema::dropIfExists('charles_marketing_expertises');
    }
}
