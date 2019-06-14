<?php namespace Charles\Crm\Updates;

use Charles\Crm\Models\Client;
use Charles\Crm\Models\Commercial;
use Charles\Crm\Models\Gamme;
use Charles\Crm\Models\Region;
use Charles\Crm\Models\Sale;
use Charles\Mailgun\Models\Contact;


use Dom\Crm\Models\Provider;
use October\Rain\Database\Updates\Seeder;
use Faker;

class SeedAllTables extends Seeder
{
    public function run()
    {
        $nbCommercials = 50;
        $nbGammes = 6;
        $nbClients = 2000;
        $nbSales = 25000;
        Region::create(['name' => 'Nord']);
        Region::create(['name' => 'Centre']);
        Region::create(['name' => 'Ouest']);
        Region::create(['name' => 'Est']);
        Region::create(['name' => 'Ile de France']);
        //
        Gamme::create(['name' => 'Start']);
        Gamme::create(['name' => 'Flex']);
        Gamme::create(['name' => 'UpperService']);
        Gamme::create(['name' => 'YellowSub']);
        Gamme::create(['name' => 'NewEx']);

        $faker = Faker\Factory::create('fr_FR');
        // for ($i = 0; $i < $nbGammes; $i++) {
        //     Gamme::create([
        //         'name' => $faker->unique()->safeColorName,
        //          ]);
        // }
        for ($i = 0; $i < $nbCommercials; $i++) {
            Commercial::create([
                'name' => $faker->unique()->name,
                'region_id' =>  $faker->numberBetween(1,5)
                 ]);
        }
        for ($i = 0; $i < $nbClients; $i++) {
            Client::create([
                'name' => $faker->unique()->company,
                'commercial_id' =>  $faker->numberBetween(1,$nbCommercials)
                 ]);
        } 
        for ($i = 0; $i < $nbSales; $i++) {
            Sale::create([
                'amount' => $faker->numberBetween(100,10000),
                'client_id' =>  $faker->numberBetween(1,$nbClients),
                'gamme_id' =>  $faker->numberBetween(1,$nbGammes),
                'sale_date' =>  $faker->dateTimeThisYear('2019-12-31 23:00:00')
                 ]);
        }
        $commercials = Commercial::get();
        foreach($commercials as $commercial) {
            trace_log($commercial->region->id);
            $regionId = $commercial->region->id;
            $sales = $commercial->sales()->get();
            foreach($sales as $sale) {
                trace_log("sales ". $sale->id);
               $sale->update(['region_id' => $regionId]);
            }




        }
        $contacts = Contact::get();  
        foreach($contacts as $contact) {
               $contact->update(['region_id' => $faker->numberBetween(1,5)]);
        }
    } 
}