<?php namespace Charles\Mailgun\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Charles\Marketing\Models\Client;
use Charles\Mailgun\Models\Contact;
use Charles\Mailgun\Models\Cloudi;
use Charles\Marketing\Models\Settings;
//
use Cloudder;
use Flash;
use Redirect;

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
        'Charles.Mybehaviors.Behaviors.PdfCvExport',
        'Backend.Behaviors.RelationController',

    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $duplicateConfig = 'config_duplicate.yaml';
    public $pdfConfig = 'config_pdfexport.yaml';
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Charles.Mailgun', 'mailgun', 'side-menu-contacts');
    }
}
