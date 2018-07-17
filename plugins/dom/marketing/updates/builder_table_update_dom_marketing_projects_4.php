<?php namespace Dom\Marketing\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDomMarketingProjects4 extends Migration
{
    public function up()
    {
        Schema::table('dom_marketing_projects', function($table)
        {
            $table->increments('id')->change();
        });
    }
    
    public function down()
    {
        Schema::table('dom_marketing_projects', function($table)
        {
            $table->integer('id')->change();
        });
    }
}
