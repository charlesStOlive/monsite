<?php namespace Charles\Troisd\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Scenes Back-end Controller
 */
class Scenes extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController',
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Charles.Troisd', 'troisd', 'side-menu-scenes');
    }
}
