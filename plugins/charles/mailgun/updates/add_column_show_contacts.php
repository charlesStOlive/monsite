<?php namespace Charles\Mailgun\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class addColumnShowContacts extends Migration
{
    public function up()
    {
        Schema::table('charles_mailgun_contacts', function($table)
        {
            $table->boolean('show_intro')->default(true);
            $table->boolean('show_message_perso')->default(false);
            
        });
    }

    public function down()
    {
        Schema::table('charles_mailgun_contacts', function($table)
        {
            $table->dropColumn('show_intro');
            $table->dropColumn('show_message_perso');
        });
    }
}