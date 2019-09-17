<?php namespace Charles\Mailgun\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class addColumnContactsLm extends Migration
{
    public function up()
    {
        Schema::table('charles_mailgun_contacts', function($table)
        {
            $table->text('messages_lm')->nullable(); 
        });
    }

    public function down()
    {
        Schema::table('charles_mailgun_contacts', function($table)
        {
            $table->dropColumn('messages_lm');
        });
    }
}