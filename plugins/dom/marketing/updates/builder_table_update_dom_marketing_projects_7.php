<?php namespace Dom\Marketing\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDomMarketingProjects7 extends Migration
{
    public function up()
    {
        Schema::table('dom_marketing_projects', function($table)
        {
            $table->integer('year')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('dom_marketing_projects', function($table)
        {
            $table->dropColumn('year');
        });
    }
}
