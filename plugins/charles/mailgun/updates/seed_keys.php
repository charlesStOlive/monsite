<?php namespace Charles\Mailgun\Updates;


use Charles\Mailgun\Models\Contact;


use Dom\Crm\Models\Provider;
use October\Rain\Database\Updates\Seeder;

class SeedKeys extends Seeder
{
    public function run()
    {
        $contacts = Contact::get();  
        foreach($contacts as $contact) {
               $contact->update(['key' => str_random(12)]);
        }
    } 
}