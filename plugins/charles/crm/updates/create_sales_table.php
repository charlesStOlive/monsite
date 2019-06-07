<?php namespace Charles\Crm\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateSalesTable extends Migration
{
    public function up()
    {
        Schema::create('charles_crm_sales', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('amount');
            $table->integer('client_id')->unsigned();
            $table->integer('gamme_id')->unsigned();
            $table->dateTime('sale_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('charles_crm_sales');
    }
}
