<?php namespace Charles\Marketing\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateExperiencesTable extends Migration
{
    public function up()
    {
        Schema::create('charles_marketing_experiences', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->text('options')->nullable();
            $table->integer('sort_order')->nullable();
            $table->timestamps();
        });


        Schema::create('charles_marketing_experience_competence', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('competence_id')->unsigned();
            $table->integer('experience_id')->unsigned();
            $table->primary(['competence_id', 'experience_id' ], 'experiences_competence');
            $table->timestamps();
        });

        Schema::create('charles_marketing_experience_project', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('project_id')->unsigned();
            $table->integer('experience_id')->unsigned();
            $table->primary(['project_id', 'experience_id' ], 'experience_project');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('charles_marketing_experiences');
        Schema::dropIfExists('charles_marketing_experience_competence');
        Schema::dropIfExists('charles_marketing_experience_project');

    }
}