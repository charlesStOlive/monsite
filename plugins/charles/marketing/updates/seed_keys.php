<?php namespace Charles\Marketing\Updates;


use DB;
use Dom\Crm\Models\Provider;
use October\Rain\Database\Updates\Seeder;

class SeedKeys extends Seeder
{
    public function run()
    {
        $sql = storage_path('app/src/charles_marketing_secteurs.sql');
        DB::unprepared(file_get_contents($sql));
        
    } 
}