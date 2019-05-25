<?php namespace Charles\Marketing\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateClientsAddOptioncvPhp extends Migration
{
    public function up()
    {
        Schema::table('charles_marketing_clients', function($table)
        {
            $table->boolean('is_cv_option')->default(0);
            $table->text('cv_option')->nullable();
            
        });
    }

    public function down()
    {
        Schema::table('charles_marketing_clients', function($table)
        {
            $table->dropColumn('is_cv_option');
            $table->dropColumn('cv_option');
        });
    }
}