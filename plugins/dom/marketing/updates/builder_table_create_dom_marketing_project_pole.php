<?php namespace Dom\Marketing\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDomMarketingProjectPole extends Migration
{
    public function up()
    {
        Schema::create('dom_marketing_project_pole', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('project_id');
            $table->integer('pole_id');
            $table->primary(['project_id','pole_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('dom_marketing_project_pole');
    }
}
