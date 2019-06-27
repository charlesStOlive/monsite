<?php namespace Charles\Marketing\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateProjectsTableAddShowCarouselPhp extends Migration
{
    public function up()
    {
        Schema::table('charles_marketing_projects', function($table)
        {
            $table->integer('show_carousel')->default(false);
        });
    }

    public function down()
    {
        Schema::table('charles_marketing_projects', function($table)
        {
            $table->dropColumn('show_carousel');
        });
    }
}