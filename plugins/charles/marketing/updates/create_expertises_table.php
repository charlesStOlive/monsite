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
            $table->text('header_description')->nullable();
            $table->text('header_link')->nullable();
            $table->timestamps();
        });

        Schema::create('charles_marketing_competence_expertise', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('competence_id')->unsigned();
            $table->integer('expertise_id')->unsigned();
            $table->primary(['competence_id', 'expertise_id' ], 'competence_expertise');
            $table->timestamps();
        });

        


    }

    public function down()
    {
        Schema::dropIfExists('charles_marketing_expertises');
        Schema::dropIfExists('charles_marketing_competence_expertise');
    }
}
