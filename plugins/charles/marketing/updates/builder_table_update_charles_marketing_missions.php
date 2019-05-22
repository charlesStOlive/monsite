<?php namespace Charles\Marketing\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCharlesMarketingMissions extends Migration
{
    public function up()
    {
        Schema::table('charles_marketing_missions', function($table)
        {
            $table->boolean('show_home')->nullable()->default(0);
            $table->text('nested_info')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('charles_marketing_missions', function($table)
        {
            $table->dropColumn('show_home');
            $table->dropColumn('nested_info');
        });
    }
}
