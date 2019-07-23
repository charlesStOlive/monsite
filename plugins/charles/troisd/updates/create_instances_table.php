<?php namespace Charles\Troisd\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateInstancesTable extends Migration
{
    public function up()
    {
        Schema::create('charles_troisd_instances', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('charles_troisd_instances');
    }
}
