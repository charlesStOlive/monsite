<?php namespace Dom\Marketing\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDomMarketingPole extends Migration
{
    public function up()
    {
        Schema::create('dom_marketing_pole', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 20);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('dom_marketing_pole');
    }
}
