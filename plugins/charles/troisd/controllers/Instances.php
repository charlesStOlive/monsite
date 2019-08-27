<?php namespace Charles\Troisd\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Instances Back-end Controller
 */
class Instances extends Controller
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

        BackendMenu::setContext('Charles.Troisd', 'troisd', 'instances');
    }
}
