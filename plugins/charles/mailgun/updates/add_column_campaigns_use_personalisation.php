<?php namespace Charles\Mailgun\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class addColumnCampaignsUsePersonalisation extends Migration
{
    public function up()
    {
        Schema::table('charles_mailgun_campaigns', function($table)
        {
            $table->boolean('use_personalisation')->default(false); 
        });
    }

    public function down()
    {
        Schema::table('charles_mailgun_campaigns', function($table)
        {
            $table->dropColumn('use_personalisation');
        });
    }
}