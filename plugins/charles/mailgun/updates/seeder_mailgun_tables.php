<?php namespace Charles\Crm\Updates;

use Seeder;
use Excel;
use DB;

class SeederMailgunTables extends Seeder
{
    public function run()
    {
        $sql = base_path('plugins/charles/mailgun/updates/sql/dump.sql');
        //collect contents and pass to DB::unprepared
        DB::unprepared(file_get_contents($sql));

    }
}