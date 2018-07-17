<?php namespace Dom\Marketing\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDomMarketingProjectJob extends Migration
{
    public function up()
    {
        Schema::create('dom_marketing_project_job', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('project_id');
            $table->integer('job_id');
            $table->primary(['project_id','job_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('dom_marketing_project_job');
    }
}
