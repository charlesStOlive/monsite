<?php namespace Charles\Troisd\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateHpsTagsTable extends Migration
{
    public function up()
    {
        Schema::create('charles_troisd_hps_tags', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('hp_id')->unsigned();
            $table->integer('tag_id')->unsigned();
            $table->primary(['hp_id', 'tag_id'], 'hp_tag');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('charles_troisd_hps_tags');
    }
}
