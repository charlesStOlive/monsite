<?php namespace Charles\Mailgun\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Event;
use Flash;
use Redirect;
use System\Classes\SettingsManager;

use Charles\Mailgun\Models\Campaign;


/**
 * Campaigns Back-end Controller
 */
class Campaigns extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController',
        'Charles.Mailgun.Behaviors.SendEmails',
        'Charles.Mybehaviors.Behaviors.DuplicateModel'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';
    public $duplicateConfig = 'config_duplicate.yaml';

    public $requiredPermissions = ['charles.mailgun.*'];



    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Charles.Mailgun', 'mailgun', 'side-menu-campaigns');
        // SettingsManager::setContext('charles.mailgun', 'settings');

    }

}
