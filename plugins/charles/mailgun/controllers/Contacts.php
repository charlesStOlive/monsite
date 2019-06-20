<?php namespace Charles\Mailgun\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Charles\Marketing\Models\Client;
use Charles\Mailgun\Models\Contact;
use Charles\Mailgun\Models\Cloudi;
use Charles\Marketing\Models\Settings;
//
use Cloudder;
use Flash;
use Redirect;

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
        'Backend.Behaviors.RelationController',

    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $duplicateConfig = 'config_duplicate.yaml';
    public $pdfConfig = 'config_pdfexport.yaml';
    public $relationConfig = 'config_relation.yaml';

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

    public function onLoadCreateGroupeCloudis()
    {
        $contactsChecked = post('checked');
        
        foreach($contactsChecked as $id) {
            $this->createContactImage($id); 
        }
        return Redirect::refresh();
        
    }

    public function onCreateUniqueContactImage()
    {
        $id = post('id');
        $this->createContactImage($id);
        return Redirect::refresh();
        
    }
    

    public function createContactImage($id='null') {
        if($id == 'null') {
            $id = post('id');
        }
        $contact = Contact::find($id);

        $client = $contact->client;
        //Suppresion des cloudis existant : 
        $contact->cloudis()->detach();

        $settings = Settings::instance()->value;
        //Gestion de la couleur.
        $colorClient = $settings['base_color'];
        if($client) {
            if($client->base_color) $colorClient = $client->base_color;
        }
        //cloudinary utilise des couleurs sans le # on l'enlève. 
        $colorClient = substr($colorClient, 1);

        $cloudis = Cloudi::where('is_client',0)->get();

        foreach($cloudis as $cloudi) {
            $recError = false;
            $url="";
            if($cloudi->is_client_needed && !$client) {
                $recError = true;
            } else if($cloudi->is_logo_needed) {
                if(!$client->cloudiLogoExiste) $recError = true;
            } 
            trace_log("recError ".$recError);
            if(!$recError) {
                $pivotData = ['url' => Cloudder::secureShow($cloudi->path, eval($cloudi->config))];
                $contact->cloudis()->add($cloudi, $pivotData);
            }
        }
        Flash::success('Info OK');
    }
    public function onTestContactImage($id='null') {
        if($id == 'null') {
            $id = post('id');
        }
        $contact = Contact::find($id);

        $client = $contact->client;
        //Suppresion des cloudis existant : 

        $settings = Settings::instance()->value;

        trace_log($settings );
        $colorClient = $settings['base_color'];
        
        if(!$client) {
            return Redirect::refresh()->with('message', 'Il manque un client');
        } else {
            $colorClient = $client->base_color;
        }
       
        //cloudinary utilise des couleurs sans le # on l'enlève. 
        $colorClient = substr($colorClient, 1);

        $myOpt =  [
            "transformation"=>[
                    ["width"=>300, "crop"=>"lfill"],
                    [   "overlay"=>"client_logo_".$client->slug,
                            "height"=>100, 
                            "effect"=>"multiply",
                            "width"=>100,
                            "y"=>0,
                            "crop"=>"scale"
                        ], 
                    [
                    "overlay"=>[
                        "font_family"=>"arial",
                        "font_size"=>10,
                        "text"=>"Préface de ".$contact->name. " ".$contact->fname
                        ],
                    "width" => 150,
                    "crop"=>"scale",
                    "y" => "65",
                    ],
                    ["effect"=>"replace_color:$colorClient:20:00e831"],
            ]
        ];
        $url = Cloudder::secureShow('campagne/book/livre_mail', $myOpt);
        trace_log($myOpt);
        trace_log($url);
        $this->vars['src'] = $url;
        return $this->makePartial('$/charles/marketing/controllers/clients/_img_form.htm');
    }
}
