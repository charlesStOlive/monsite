<?php namespace Charles\Mail\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateMailgunTables extends Migration
{
    public function up()
    {
        Schema::create('charles_mailgun_campaigns', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('subject')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->integer('status_id')->default(1)->index();
            $table->text('messages')->nullable();
            $table->text('default')->nullable();
            $table->string('picture_1')->nullable();
            $table->string('picture_2')->nullable();
            $table->integer('nb_email_sent')->unsigned()->nullable()->default(0);
            $table->timestamps();
        });

        Schema::create('charles_mailgun_campaign_contact', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('contact_id')->unsigned();
            $table->integer('campaign_id')->unsigned();
            $table->text('perso')->nullable();
            $table->string('email')->nullable();
            $table->string('mg_timestamp')->nullable();
            $table->string('result_type')->nullable();

            $table->primary(['contact_id', 'campaign_id'], 'contact_campaign');
            $table->timestamps();
        });

        Schema::create('charles_mailgun_statuses', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        

    }

    public function down()
    {
        Schema::dropIfExists('charles_mailgun_campaigns');
        Schema::dropIfExists('charles_mailgun_campaign_contact');
        Schema::dropIfExists('charles_mailgun_statuses');

    }
}
