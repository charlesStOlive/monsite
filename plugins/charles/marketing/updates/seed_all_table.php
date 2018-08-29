<?php namespace Charles\Marketing\Updates;

use Seeder;
use Excel;

use Charles\Marketing\Models\Competence; 
use Charles\Marketing\Models\Competencetype;

class SeedAllTable extends Seeder
{
    public function run()
    {
    	Excel::load('plugins/charles/marketing/updates/import_excel/competences.xlsx', function($reader) {
            // Getting all results
            $results = $reader->get();
            if(!empty($results) && $results->count()){
                    Competence::truncate();
                    foreach ($results as $key => $value) {
                        Competence::create([
                        'id'  =>  $value->id,
                        'name'  =>  $value->name,
                        'slug' => str_slug($value->name),
                        'competencetype_id' => $value->competencetype_id,

                         ]);
                    }
            }
        });

        Excel::load('plugins/charles/marketing/updates/import_excel/competencestypes.xlsx', function($reader) {
            // Getting all results
            $results = $reader->get();
            if(!empty($results) && $results->count()){
                    Competencetype::truncate();
                    foreach ($results as $key => $value) {
                        Competencetype::create([
                        'id'  =>  $value->id,
                        'name'  =>  $value->name,
                        'slug' => str_slug($value->name),
                         ]);
                    }
            }
        });


    }
}