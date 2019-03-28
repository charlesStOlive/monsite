<?php namespace Charles\Marketing\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddAccrocheToProjectandMission extends Migration
{
    public function up()
    {
        Schema::table('charles_marketing_projects', function($table)
        {
            $table->string('accroche')->nullable();
        });
         Schema::table('charles_marketing_missions', function($table)
        {
            $table->string('accroche')->nullable();
        });
    }

    public function down()
    {
        Schema::table('charles_marketing_projects', function($table)
        {
            $table->dropColumn('accroche');
        });
         Schema::table('charles_marketing_missions', function($table)
        {
            $table->dropColumn('accroche');
        });
    }
}