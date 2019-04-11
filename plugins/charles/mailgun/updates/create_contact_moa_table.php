<?php namespace Charles\Mailgun\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateContactMoaTable extends Migration
{
    public function up()
    {

        Schema::create('charles_mailgun_contact_moa', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('contact_id')->unsigned();
            $table->integer('moa_id')->unsigned();
            $table->primary(['contact_id', 'moa_id'], 'contact_moa');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('charles_mailgun_contact_moa');
    }
}