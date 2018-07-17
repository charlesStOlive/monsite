<?php namespace Dom\Marketing\Updates;

use Dom\Marketing\Models\Client;
use October\Rain\Database\Updates\Seeder;
use Faker;

class SeedAllTables extends Seeder
{

    public function run() {

        $faker = Faker\Factory::create();

        for($i = 0; $i < 100; $i++) {
            Client::create([
                'name' => $faker->sentence($nbWords = 2, $variableNbWords = true),
                'slug' => str_slug($faker->sentence($nbWords = 2, $variableNbWords = true), '-'),
                'description' => $faker->paragraph($nbSentences = 5, $variableNbSentences = true),
                'city' => $faker->sentence($nbWords = 1, $variableNbWords = false),
                'address' => $faker->sentence($nbWords = 4, $variableNbWords = true),
                'address' => $faker->sentence($nbWords = 4, $variableNbWords = true)
            ]);
        }

    }

}
