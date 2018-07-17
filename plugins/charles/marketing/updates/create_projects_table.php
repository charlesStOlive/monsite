<?php namespace Charles\Marketing\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateProjectsTable extends Migration
{
    public function up()
    {
        Schema::create('charles_marketing_projects', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->integer('client_id')->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('charles_marketing_expertises_project', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('project_id')->unsigned();
            $table->integer('expertise_id')->unsigned();
            $table->primary(['expertise_id', 'project_id' ], 'expertise_project');
            $table->timestamps();
        });

        Schema::create('charles_marketing_competence_project', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('project_id')->unsigned();
            $table->integer('competence_id')->unsigned();
            $table->primary(['competence_id', 'project_id'], 'competence_project');
            $table->timestamps();
        });


    }

    public function down()
    {
        Schema::dropIfExists('charles_marketing_projects');
        Schema::dropIfExists('charles_marketing_expertises_project');
        Schema::dropIfExists('charles_marketing_competence_project');
    }
}
