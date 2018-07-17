<?php namespace Dom\Marketing\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDomMarketingClientSector extends Migration
{
    public function up()
    {
        Schema::create('dom_marketing_client_sector', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('client_id');
            $table->integer('sector_id');
            $table->primary(['client_id','sector_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('dom_marketing_client_sector');
    }
}
