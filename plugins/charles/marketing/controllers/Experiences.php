<?php namespace Charles\Marketing\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Experiences Back-end Controller
 */
class Experiences extends Controller
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

        BackendMenu::setContext('Charles.Marketing', 'experience', 'side-menu-experiences');
    }
}
