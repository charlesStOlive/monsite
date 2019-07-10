<?php namespace Charles\Mailgun\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Charles\Marketing\Models\Client;
use Charles\Mailgun\Models\Contact;
use Charles\Mailgun\Models\Visit;
use Charles\Mailgun\Models\Cloudi;
use Charles\Marketing\Models\Settings;
//
use Cloudder;
use Flash;
use Redirect;
use Session;


/**
 * Contacts Back-end Controller
 */
class Contacts extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Charles.Mailgun.Behaviors.SendEmails',
        'Charles.Mailgun.Behaviors.CloudisMethods',
        'Charles.Mybehaviors.Behaviors.DuplicateModel',
        'Charles.Mybehaviors.Behaviors.ActionExport',
        'Backend.Behaviors.RelationController',

    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $duplicateConfig = 'config_duplicate.yaml';
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Charles.Mailgun', 'mailgun', 'side-menu-contacts');
    }

    public function listInjectRowClass($record, $definition)
    {
        // Injection de style CSS selon$record
        if ($record->visits()->count()) {
            return 'positive important';
        }
    }

    public function onRemoveStats()
    {
        /**
         * [$id du model]
         * @var string
         */
        Visit::where('contact_id', post('id'))->delete();
        return Redirect::Refresh()
;

    }
}
