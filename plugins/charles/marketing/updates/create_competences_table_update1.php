<?php namespace Charles\Marketing\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateCompetencesTableUpdate1 extends Migration
{
    public function up()
    {
        Schema::table('charles_marketing_competences', function($table)
        {
            $table->integer('sort_order')->nullable()->default(100);
        });
    }

    public function down()
    {
        Schema::table('charles_marketing_competences', function($table)
        {
            $table->dropColumn('sort_order');
        });
    }
}