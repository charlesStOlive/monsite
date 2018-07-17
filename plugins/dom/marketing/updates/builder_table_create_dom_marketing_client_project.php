<?php namespace Dom\Marketing\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDomMarketingClientProject extends Migration
{
    public function up()
    {
        Schema::create('dom_marketing_client_project', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('client_id');
            $table->integer('project_id');
            $table->primary(['client_id','project_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('dom_marketing_client_project');
    }
}
