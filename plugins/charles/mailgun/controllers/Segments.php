<?php namespace Charles\Mailgun\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Segments Back-end Controller
 */
class Segments extends Controller
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

        BackendMenu::setContext('Charles.Mailgun', 'mailgun', 'side-menu-segments');
    }
}
