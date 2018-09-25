<?php namespace Dom\Folies\Models;

use Model;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'dom_mailgun_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';
}