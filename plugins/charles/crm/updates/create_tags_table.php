<?php namespace Charles\Crm\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateTagsTable extends Migration
{
    public function up()
    {
        Schema::create('charles_crm_tags', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('charles_crm_contact_tag', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('contact_id');
            $table->string('tag_id');
            $table->primary(['contact_id', 'tag_id'], 'contact_tag');
        });
    }

    public function down()
    {
        Schema::dropIfExists('charles_crm_tags');
        Schema::dropIfExists('charles_crm_contact_tag');
    }
}
