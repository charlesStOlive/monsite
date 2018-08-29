<?php namespace Charles\Marketing\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateExpertisesTableUpdate1 extends Migration
{
    public function up()
    {
        Schema::table('charles_marketing_expertises', function($table)
        {
            $table->string('color')->nullable();
        });
    }

    public function down()
    {
        Schema::table('charles_marketing_expertises', function($table)
        {
            $table->dropColumn('color');
        });
    }
}