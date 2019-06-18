<?php namespace Charles\Mailgun\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class createCloudisAddBoolClient extends Migration
{
    public function up()
    {
        Schema::table('charles_mailgun_cloudis', function($table)
        {
            $table->boolean('is_client')->default(true);
        });
    }

    public function down()
    {
        Schema::table('charles_mailgun_cloudis', function($table)
        {
            $table->dropColumn('is_client');
        });
    }
}