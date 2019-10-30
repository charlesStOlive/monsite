<?php namespace Charles\Marketing\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class ExtClientAddCloudiid extends Migration
{
    public function up()
    {
        Schema::table('charles_marketing_clients', function($table)
        {
            $table->integer('cloudi_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('charles_marketing_clients', function($table)
        {
            $table->dropColumn('cloudi_id');
        });       
    }
}