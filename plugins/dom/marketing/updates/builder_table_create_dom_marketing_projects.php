<?php namespace Dom\Marketing\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDomMarketingProjects extends Migration
{
    public function up()
    {
        Schema::create('dom_marketing_projects', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('id');
            $table->string('name', 200);
            $table->string('slug', 200);
            $table->string('description', 1000)->nullable();
            $table->primary(['id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('dom_marketing_projects');
    }
}
