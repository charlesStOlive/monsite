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


    
    // cette fonction n'est plus utilisé
    public function createClientImage($id='null') {
        if($id == 'null') {
            $id = post('id');
        }
        $client = Client::find($id);
        //Suppresion des cloudis existant : 
        $client->cloudis()->detach();

        $colorClient = substr($client->base_color, 1);

        $cloudis = Cloudi::where('is_client',1)->get();

        foreach($cloudis as $cloudi) {
            $url="";
            if($cloudi->name == "book") {
                $myOpt =  [
                    "transformation"=>[
                        [   "overlay"=>"client_logo_".$client->slug,
                            "height"=>250, 
                            "effect"=>"multiply",
                            "width"=>250,
                            "y"=>40,
                            "crop"=>"limit"
                        ],
                        [
                            "height"=>400,
                            "width"=>300,
                            "crop"=>"lfill"
                        ] ,
                        ["effect"=>"replace_color:$colorClient:20:00e831"],
                    ]
                ];
                $url = Cloudder::secureShow('campagne/book/livre_plat', $myOpt);
            };
            if($cloudi->name == "bookmail") {
                $myOpt =  [
                    "transformation"=>[
                        [   "overlay"=>"client_logo_".$client->slug,
                            "height"=>50, 
                            "effect"=>"multiply",
                            "width"=>50,
                            "y"=>10,
                            "crop"=>"limit"
                        ],
                        [
                            "width"=>200,
                            "crop"=>"lfill"
                        ] ,
                        ["effect"=>"replace_color:$colorClient:20:00e831"],
                    ]
                ];
                $url = Cloudder::secureShow('campagne/book/livre_mail', $myOpt);
            };
            if($cloudi->name == "banksi") {
                    $myOpt =  [
                        "transformation"=>[
                                ["width"=>500, "crop"=>"scale"],
                                ["effect"=>"replace_color:$colorClient:40:9e3544"], 
                                ["angle"=>330,
                                'x'=>50,
                                'color' => "#28282890",
                                "effect"=>"multiply",
                                "overlay"=>[
                                    "font_family"=>"Verdana",
                                    "font_size"=>30,
                                    "font_weight"=>"bold",
                                    
                                    "text"=>$client->name,
                                ]],
                                ["overlay"=>"client_logo_".$client->slug,
                                "effect"=>"multiply",
                                "angle"=>358,
                                "width"=>150,
                                "height"=>100,
                                "gravity"=>"north_west",
                                "crop"=>"fit",
                                'x'=>120,
                                'y'=>40]
                        ]
                    ];
                    $url = Cloudder::secureShow('campagne/banksi/banksi_original', $myOpt);
            };
            $pivotData = ['url' => $url];
            $client->cloudis()->add($cloudi, $pivotData);

        }
        Flash::success('Info OK');
    }
    public function onTestClientImage($id='null') {
        if($id == 'null') {
            $id = post('id');
        }
        $client = Client::find($id);
        
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
                        "text"=>$client->contacts()->first()->name
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
                        "text"=>$client->contacts()->first()->fname
                        ],
                    "width" => 150,
                    "crop"=>"lfill",
                    "y" => "30",
                    
                    ],
            ]
        ];
        $url = Cloudder::secureShow('campagne/book/livre_mail', $myOpt);
        trace_log($myOpt);
        trace_log($url);
        $this->vars['src'] = $url;
        return $this->makePartial('$/charles/marketing/controllers/clients/_img_form.htm');
    }
}
