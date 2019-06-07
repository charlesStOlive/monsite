<?php namespace Charles\Crm\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateClientsTable extends Migration
{
    public function up()
    {
        Schema::create('charles_crm_clients', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('commercial_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('charles_crm_clients');
    }
}
