<?php namespace Charles\Mailgun\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateContactManyTables extends Migration
{
    public function up()
    {

        Schema::create('charles_mailgun_contact_project', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('contact_id')->unsigned();
            $table->integer('project_id')->unsigned();
            $table->primary(['contact_id', 'project_id'], 'contact_project');
            $table->timestamps();
        });

        Schema::create('charles_mailgun_contact_mission', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('contact_id')->unsigned();
            $table->integer('mission_id')->unsigned();
            $table->primary(['contact_id', 'mission_id'], 'contact_mission');
            $table->timestamps();
        });

        Schema::table('charles_mailgun_contacts', function($table)
        {
            $table->integer('target_id')->nullable();
        });

        

    }

    public function down()
    {
        Schema::dropIfExists('charles_mailgun_contact_project');
        Schema::dropIfExists('charles_mailgun_contact_mission');
        Schema::table('charles_mailgun_contacts', function($table)
        {
            $table->dropColumn('target_id');
        });

    }
}