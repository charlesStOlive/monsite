<?php namespace Dom\Marketing\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDomMarketingProjectSector extends Migration
{
    public function up()
    {
        Schema::create('dom_marketing_project_sector', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('project_id');
            $table->integer('sector_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('dom_marketing_project_sector');
    }
}
