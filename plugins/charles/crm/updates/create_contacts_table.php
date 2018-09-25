<?php namespace Charles\Crm\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateContactsTable extends Migration
{
    public function up()
    {
        Schema::create('charles_crm_contacts', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->string('featured_image');
            $table->string('short_name');
            $table->integer('compagny_id')->unsigned()->nullable();
            $table->string('family_name');
            $table->string('civ')->nullable();
            $table->string('email');
            $table->boolean('optin')->nullable()->default(1);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('charles_crm_contacts');
    }
}
