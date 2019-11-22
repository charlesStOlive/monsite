<?php namespace Charles\Mailgun\Models;

use Model;


class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'charles_mailgun_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';

    public function listContacts($fieldName, $value, $formData)
    {
        $contacts =  Contact::HasClientFilter(2)->get();
        $list = new \October\Rain\Support\Collection();
        foreach($contacts as $contact) {
            $list->put($contact->id,  $contact->name.' '.$contact->fname.' || '.$contact->client->name);

        }
        return $list;
    }
    public function listCloudinaris()
    {
        return Cloudi::lists('name', 'slug');
    }

    
}