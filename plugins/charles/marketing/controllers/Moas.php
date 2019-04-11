<?php namespace Charles\Marketing\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Moas Back-end Controller
 */
class Moas extends Controller
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

        BackendMenu::setContext('Charles.Marketing', 'marketing', 'side-menu-moas');
    }
}
