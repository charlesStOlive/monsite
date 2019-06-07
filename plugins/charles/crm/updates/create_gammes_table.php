<?php namespace Charles\Crm\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateGammesTable extends Migration
{
    public function up()
    {
        Schema::create('charles_crm_gammes', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('charles_crm_gammes');
    }
}
