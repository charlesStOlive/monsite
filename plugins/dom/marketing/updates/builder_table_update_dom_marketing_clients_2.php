<?php namespace Dom\Marketing\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDomMarketingClients2 extends Migration
{
    public function up()
    {
        Schema::table('dom_marketing_clients', function($table)
        {
            $table->string('description', 2000)->change();
        });
    }
    
    public function down()
    {
        Schema::table('dom_marketing_clients', function($table)
        {
            $table->string('description', 500)->change();
        });
    }
}
