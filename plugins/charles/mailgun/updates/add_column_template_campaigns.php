<?php namespace Charles\Mailgun\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class addColumnTemplateCampaigns extends Migration
{
    public function up()
    {
        Schema::table('charles_mailgun_campaigns', function($table)
        {
            $table->string('template')->default(true);
        });
    }

    public function down()
    {
        Schema::table('charles_mailgun_campaigns', function($table)
        {
                $table->dropColumn('template');
        });
    }
}