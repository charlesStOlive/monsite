<?php namespace Charles\Marketing\Models;

use Model;
use Charles\Marketing\Models\Competence;
use Charles\Mailgun\Models\Contact;
use Charles\Mailgun\Models\Cloudi;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'charles_marketing_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';

    public function listTechnical($fieldName, $value, $formData)
    {
        return Competence::CompetencetypeFilter([1,2,3,4])->lists('name', 'id');
    }
    public function listMarketing($fieldName, $value, $formData)
    {
        return Competence::CompetencetypeFilter([5])->lists('name', 'id');
    }
    public function listSoftSkills($fieldName, $value, $formData)
    {
        return Competence::CompetencetypeFilter([6])->lists('name', 'id');
    }
    public function listFonctionel($fieldName, $value, $formData)
    {
        return Competence::CompetencetypeFilter([7])->lists('name', 'id');
    }
    public function listContact($fieldName, $value, $formData)
    {
        return Contact::lists('email', 'id');
    }
}