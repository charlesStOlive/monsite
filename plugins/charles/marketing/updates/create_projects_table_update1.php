<?php namespace Charles\Marketing\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateProjectsTableUpdate1 extends Migration
{
    public function up()
    {
        Schema::table('charles_marketing_projects', function($table)
        {
            $table->integer('sort_order')->nullable();
        });
    }

    public function down()
    {
        Schema::table('charles_marketing_projects', function($table)
        {
            $table->dropColumn('sort_order');
        });
    }
}