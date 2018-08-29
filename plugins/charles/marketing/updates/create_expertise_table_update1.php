<?php namespace Charles\Marketing\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateExpertiseTableUpdate1 extends Migration
{
    public function up()
    {
        Schema::table('charles_marketing_expertises', function($table)
        {
            $table->text('description_2')->nullable();
        });
    }

    public function down()
    {
        Schema::table('charles_marketing_expertises', function($table)
        {
            $table->dropColumn('description_2');
        });
    }
}