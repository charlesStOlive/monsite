<?php namespace Charles\Marketing\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class updateProjectsTable_1 extends Migration
{
    public function up()
    {
        Schema::table('charles_marketing_projects', function($table)
        {
            $table->text('video')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('charles_marketing_projects', function($table)
        {
            $table->text('video')->nullable();
        });
    }
}