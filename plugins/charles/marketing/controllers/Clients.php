<?php namespace Charles\Marketing\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Charles\Marketing\Models\Client;

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
        $pathMedia = storage_path('app/media');
        $filename = $pathMedia . $client->logo;
        $publicId = "client_logo_".$client->slug;
        trace_log(Cloudder::upload($filename, $publicId));

        // $publicId = "client_logo_".$client->slug;
        // trace_log(Cloudder::secureShow($publicId.'aaa'));
    }
    

    public function onCreateClientImage() {
        $client = Client::find(post('id'));
        trace_log($client->base_color);
        $colorClient = substr($client->base_color, 1);

        // $myOpt =  [
        //     "transformation"=>[
        //             ["width"=>500, "crop"=>"scale"],
        //             ["effect"=>"replace_color:$colorClient:40:9e3544"], 
        //             ["angle"=>330,
        //             'x'=>50,
        //             'color' => "#28282890",
        //             "effect"=>"multiply",
        //             "overlay"=>[
        //                 "font_family"=>"Verdana",
        //                 "font_size"=>30,
        //                 "font_weight"=>"bold",
                        
        //                 "text"=>$client->name,
        //             ]],
        //             ["overlay"=>"client_logo_".$client->slug,
        //             "effect"=>"multiply",
        //             "angle"=>358,
        //             "width"=>150,
        //             "height"=>100,
        //             "gravity"=>"north_west",
        //             "crop"=>"fit",
        //             'x'=>120,
        //             'y'=>40]
        //     ]
        // ];
        //$url = Cloudder::secureShow('campagne/banksi/banksi_original', $myOpt);
        //
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
                    "height"=>800,
                    "width"=>600,
                    "crop"=>"lfill"
                ] ,
                ["effect"=>"replace_color:$colorClient:20:00e831"],
            ]
        ];
        $url = Cloudder::secureShow('campagne/book/livre_plat', $myOpt);
        trace_log($myOpt);
        trace_log($url);
        $this->vars['src'] = $url;
        return $this->makePartial('$/charles/marketing/controllers/clients/_img_form.htm');
    }
}
