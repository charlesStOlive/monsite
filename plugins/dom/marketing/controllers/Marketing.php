<?php namespace Dom\Marketing\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Marketing extends Controller
{
    public $implement = [        'Backend\Behaviors\FormController',        'Backend\Behaviors\ReorderController'    ];
    
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Dom.Marketing', 'main-marketing-item');
    }
}
