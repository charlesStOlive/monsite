<?php namespace Charles\Mailgun\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateContactsTable extends Migration
{
    public function up()
    {
        Schema::create('charles_mailgun_contacts', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('client_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('charles_mailgun_contacts');
    }
}
