<?php namespace Dom\Marketing\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDomMarketingProjects6 extends Migration
{
    public function up()
    {
        Schema::table('dom_marketing_projects', function($table)
        {
            $table->string('description', 5000)->change();
        });
    }
    
    public function down()
    {
        Schema::table('dom_marketing_projects', function($table)
        {
            $table->string('description', 1000)->change();
        });
    }
}
