<?php namespace Dom\Marketing\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableDeleteDomMarketingProjectSector extends Migration
{
    public function up()
    {
        Schema::dropIfExists('dom_marketing_project_sector');
    }
    
    public function down()
    {
        Schema::create('dom_marketing_project_sector', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('project_id');
            $table->integer('sector_id');
        });
    }
}
