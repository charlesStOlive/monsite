<?php namespace Charles\Crm\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class AddRegionToContact extends Migration
{
    public function up()
    {
        Schema::table('charles_mailgun_contacts', function($table)
        {
            $table->integer('region_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('charles_mailgun_contacts', function($table)
        {
            $table->dropColumn('region_id');
        });
    }
}
