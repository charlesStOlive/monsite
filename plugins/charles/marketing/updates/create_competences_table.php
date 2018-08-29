<?php namespace Charles\Marketing\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateCompetencesTable extends Migration
{
    public function up()
    {
        Schema::create('charles_marketing_competences', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->integer('competencetype_id')->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->text('wiki_description')->nullable();
            $table->text('wiki_picture')->nullable();
            $table->string('external_link')->nullable();
            $table->boolean('error_wiki')->nullable();
            $table->boolean('disabled_wiki')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('charles_marketing_competences');
    }
}
