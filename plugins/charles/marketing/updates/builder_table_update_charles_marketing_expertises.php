<?php namespace Charles\Marketing\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCharlesMarketingExpertises extends Migration
{
    public function up()
    {
        Schema::table('charles_marketing_expertises', function($table)
        {
            $table->string('img_featured')->nullable();
            $table->string('icone')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('charles_marketing_expertises', function($table)
        {
            $table->dropColumn('img_featured')->nullable();
            $table->dropColumn('icone')->nullable();
        });
    }
}