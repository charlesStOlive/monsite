<?php namespace Charles\Marketing\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateSalairesTable extends Migration
{
    public function up()
    {
        Schema::create('charles_marketing_salaires', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('salaire_start')->nullable();
            $table->integer('salaire_end')->nullable();
            $table->text('description')->nullable();
            $table->text('iframe')->nullable();
            $table->string('iframe_code')->nullable();
            $table->string('link_name')->nullable();
            $table->string('link_url')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('charles_marketing_salaires');
    }
}