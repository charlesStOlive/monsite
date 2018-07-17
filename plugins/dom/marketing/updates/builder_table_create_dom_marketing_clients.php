<?php namespace Dom\Marketing\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDomMarketingClients extends Migration
{
    public function up()
    {
        Schema::create('dom_marketing_clients', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->string('city', 100)->nullable();
            $table->string('address', 100)->nullable();
            $table->string('zip_code', 100)->nullable();
            $table->string('description', 500)->nullable();
            $table->boolean('banner')->default(1);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('dom_marketing_clients');
    }
}
