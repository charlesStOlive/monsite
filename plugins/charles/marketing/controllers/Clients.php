<?php namespace Charles\Marketing\Controllers;

use BackendMenu;
use Redirect;
use Flash;
use Backend\Classes\Controller;

use Charles\Mailgun\Models\Cloudi;
//
use Charles\Marketing\Models\Client;
use Charles\Marketing\Models\Settings;
//
use Cloudder;
use ColorPalette;
use Session;

/**
 * Clients Back-end Controller
 */
class Clients extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController',
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';
    public $logoColors = null;

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Charles.Marketing', 'marketing', 'side-menu-clients');
    }

    public function onUploadCloudinary() {
        $client = Client::find(post('id'));
        $client->save();
        $client->uploadCloudinary();
    }

    public function onLoadCreateGroupeCloudis()
    {
        $clientsChecked = post('checked');
        
        foreach($clientsChecked as $id) {
            $this->createClientImage($id); 
        }
        
    }
    public function onFindColors()
    {
        $client = Client::find(post('id'));
        $colors = ColorPalette::getPalette(storage_path('app/media/'.$client->logo),6,10);
        Session::put('logo.colors', $colors);
        return Redirect::refresh();
        
    }

    // public function formExtendFields($host, $fields)
    // {
    //     $colors = Session::get('logo.colors');
    //     trace_log($colors);
    //     foreach ($fields as $field) {
    //         trace_log($field->fieldName);
    //         if($field->fieldName == 'base_color') {
    //             trace_log("Ok champs trouvé");
    //             trace_log($field->config);
    //             $field->label = "salut";
    //             $field->availableColors = $colors;
    //         }
                
    //     }
    // }
    public function formExtendFields($form)
    {
        $colors = Session::pull('logo.colors');
        $availableColors = ["#ffffff", '#000000'];
        if($colors) $availableColors = $colors;
        $form->addTabFields([
            'base_color' => [
                'label'=> 'Couleur de bases',
                'type'=> 'colorpicker',
                'span'=> 'auto',
                'availableColors'=> $availableColors,
            ],
        ]);
    }
}
