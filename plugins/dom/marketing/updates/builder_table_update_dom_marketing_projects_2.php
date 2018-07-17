<?php namespace Dom\Marketing\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDomMarketingProjects2 extends Migration
{
    public function up()
    {
        Schema::table('dom_marketing_projects', function($table)
        {
            $table->integer('client_id');
        });
    }
    
    public function down()
    {
        Schema::table('dom_marketing_projects', function($table)
        {
            $table->dropColumn('client_id');
        });
    }
}
