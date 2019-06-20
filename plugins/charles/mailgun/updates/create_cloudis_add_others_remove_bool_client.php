<?php namespace Charles\Mailgun\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class createCloudisAddOthersRemoveBoolClient extends Migration
{
    public function up()
    {
        Schema::table('charles_mailgun_cloudis', function($table)
        {
            $table->boolean('is_client_needed')->default(true);
            $table->boolean('is_logo_needed')->default(true);
            $table->text('config')->nullable();
            $table->string('function')->nullable();
            $table->string('path')->nullable();

        });
    }

    public function down()
    {
        Schema::table('charles_mailgun_cloudis', function($table)
        {
            $table->dropColumn('is_client_needed');
            $table->dropColumn('is_logo_needed');
            $table->dropColumn('config');
            $table->dropColumn('function');
            $table->dropColumn('path');

        });
    }
}