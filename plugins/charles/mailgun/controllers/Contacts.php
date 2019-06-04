<?php namespace Charles\Mailgun\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Charles\Marketing\Models\Client;

/**
 * Contacts Back-end Controller
 */
class Contacts extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Charles.Mailgun.Behaviors.SendEmails',
        'Charles.Mybehaviors.Behaviors.DuplicateModel',
        'Charles.Mybehaviors.Behaviors.PdfCvExport',

    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $duplicateConfig = 'config_duplicate.yaml';
    public $pdfConfig = 'config_pdfexport.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Charles.Mailgun', 'mailgun', 'side-menu-contacts');
    }
    /**
     * ABANDON ( INITIALISER UN MODEL DANS AUTRE)
     */
    // public function formExtendModel($model)
    // {
    //     /*
    //      * Init proxy field model if we are creating the model
    //      */
    //     if ($this->action == 'create') {
    //         $model->client = new Client;
    //     }
    //     return $model;
    // }
}
