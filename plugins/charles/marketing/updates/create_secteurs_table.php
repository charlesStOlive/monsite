<?php namespace Charles\Marketing\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateSecteursTable extends Migration
{
    public function up()
    {
        Schema::create('charles_marketing_secteurs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('slug')->nullable();
            $table->string('name')->nullable();
            $table->integer('cloudi_id')->unsigned()->nullable();
            $table->boolean('is_cv_option')->default(0);
            $table->text('cv_option')->nullable();
            $table->boolean('is_messages_lm')->default(0);
            $table->text('messages_lm')->nullable();
            $table->text('site_intro')->nullable(); 
            $table->timestamps();
        });

        Schema::table('charles_marketing_clients', function($table)
        {
            $table->integer('secteur_id')->unsigned()->default(1);
        });
    }

    public function down()
    {
        Schema::dropIfExists('charles_marketing_secteurs');
        Schema::table('charles_marketing_clients', function($table)
        {
            $table->dropColumn('secteur_id');
        });
    }
}