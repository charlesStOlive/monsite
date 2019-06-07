<?php namespace Charles\Crm\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Commercials Back-end Controller
 */
class Commercials extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Charles.Crm', 'crm', 'commercials');
    }
}
