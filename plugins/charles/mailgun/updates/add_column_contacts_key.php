<?php namespace Charles\Mailgun\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class addColumnContactsKey extends Migration
{
    public function up()
    {
        Schema::table('charles_mailgun_contacts', function($table)
        {
            $table->string('key'); 
        });
    }

    public function down()
    {
        Schema::table('charles_mailgun_contacts', function($table)
        {
            $table->dropColumn('key');
        });
    }
}