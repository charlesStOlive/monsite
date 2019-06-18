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
    public function makePdfReport($idc, $m)
    {
        /**
         * [$id du model]
         * @var string
         */
        //$id = post('id');
        //$lang = post('lang');

        if(!$id) throw new ApplicationException('Please verifiy id model to export');
        if(!$m) throw new ApplicationException('Please verifiy the month ( m ) in the url');



        // /**
        //  * [$model model de base pour l'export]
        //  * @var Collection
        //  */


        // $contact = Contact::find($id);
        // if ($contact === null) {
        //     throw new ApplicationException('model not found.');
        // }
       
        // /**
        //  * @var bool 
        //  */
        // $nestedModel = $this->getConfig('nested_model');


        // if($nestedModel) {
        //     $contact[$nestedModel] = $contact[$nestedModel]->toNested();
        //     //$contact->quotesdetails = $contact->quotesdetails->toNested();

        // }


        // /**
        //  * A modifier plus tard j'appelle en dur une liaison
        //  */
        // $contact['experiences'] =  Experience::with('projects', 'competences')->get();
        // $settings = Settings::instance()->value;
        
        // /**
        //  * TRAVAIL SUR LES OPTIONS DU CV
        //  */
        // trace_log("travail sur les options du CV");
        // $compostings = new \October\Rain\Support\Collection();
        // foreach ($contact->client->cloudis as $cloudi) {
        //     $compostings->put($cloudi->name, $cloudi->pivot->url );
        // }

        // $contact['compostings'] = $compostings;
        // if($contact->client) {
        //     if($contact->client->base_color) {
        //         $settings['base_color'] = $contact->client->base_color;
        //     }
        //     if($contact->client->is_cv_option) {
        //         $clientOption = $contact->client->cv_option;
        //         if($clientOption['color']) $settings['cv_option']['color'] = $clientOption['color'];
        //         if($clientOption['title']) $settings['cv_option']['title'] = $clientOption['title'];
        //         if($clientOption['secteur']) $settings['cv_option']['secteur'] = $clientOption['secteur'];
        //         if(array_key_exists('technical', $clientOption)) {
        //             $settings['cv_option']['technical'] = $clientOption['technical'];
        //         }
        //         if(array_key_exists('marketing', $clientOption)) $settings['cv_option']['marketing'] = $clientOption['marketing'];
        //         if(array_key_exists('soft_skills', $clientOption)) $settings['cv_option']['soft_skills'] = $clientOption['soft_skills'];
        //         if(array_key_exists('fonctionel', $clientOption)) $settings['cv_option']['fonctionel'] = $clientOption['fonctionel'];
        //     }
        // }
        // $contact['settings'] = $settings;
        // /**
        //  * Construction du pdf
        //  */
        // try {
        //     /** @var PDFWrapper $pdf */
        //     $pdf = app('dynamicpdf');

        //     $options = [
        //         'logOutputFile' => storage_path('temp/log.htm'),
        //         'isRemoteEnabled' => true,
        //     ];

        //      $pdf
        //         ->loadTemplate($templateCode, compact('data'))
        //         ->setOptions($options)
        //         ->save(storage_path('app/media/cv/'.$contact->cv_name.'.pdf'))
        //         ->stream();

        // } catch (Exception $e) {
        //     throw new ApplicationException($e->getMessage());
        // }
        return Redirect::refresh();
    }

    public function onLoadCreateGroupeCloudis()
    {
        $contactsChecked = post('checked');
        
        foreach($contactsChecked as $id) {
            $this->createContactImage($id); 
        }
        
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
        $colorClient = $settings['base_color'];
        if($client) {
            $colorClient = $client->base_color;
        }
        //cloudinary utilise des couleurs sans le # on l'enlève. 
        $colorClient = substr($colorClient, 1);

        $cloudis = Cloudi::where('is_client',0)->get();

        foreach($cloudis as $cloudi) {
            $url="";
            if($cloudi->name == "bookmailcontact") {
                $myOpt =  [
                    "transformation"=>[
                        ["width"=>300, "crop"=>"lfill"], 
                        [
                        "overlay"=>[
                            "font_family"=>"arial",
                            "font_size"=>15,
                            "font_weight"=>"bold",
                            "text"=>"Préface"
                            ],
                        "width" => 150,
                        "crop"=>"fit",
                        "y" => "-30",
                        ],
                        [
                        "overlay"=>[
                            "font_family"=>"arial",
                            "font_size"=>20,
                            "font_weight"=>"bold",
                            "text"=>$contact->name
                            ],
                        "width" => 150,
                        "crop"=>"lfill",
                        "y" => "0"
                        ],
                        [
                        "overlay"=>[
                            "font_family"=>"arial",
                            "font_size"=>20,
                            "font_weight"=>"bold",
                            "text"=>$contact->fname
                            ],
                        "width" => 150,
                        "crop"=>"lfill",
                        "y" => "30",
                        ],
                        ["effect"=>"replace_color:$colorClient:20:00e831"],
                    ]
                ];
                $url = Cloudder::secureShow('campagne/book/livre_mail', $myOpt);
            };
            if($cloudi->name == "bookmailcontactclient") {
                if(! $client) return; //pas de création d'image si pas de client. 
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
            };
            $pivotData = ['url' => $url];
            $contact->cloudis()->add($cloudi, $pivotData);

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
