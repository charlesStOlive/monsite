<?php namespace Charles\Marketing\Controllers;

use BackendMenu;
use Redirect;
use Flash;
use Str;
use Backend\Classes\Controller;

use Charles\Mailgun\Models\Cloudi;
//
use Charles\Marketing\Models\Client;
use Charles\Mailgun\Models\Contact;
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
        'Charles.Mybehaviors.Behaviors.PdfCvExport',
        'Charles.Mailgun.Behaviors.SendEmails',
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';
    public $pdfConfig = 'config_pdfexport.yaml';
    public $logoColors = null;
    //
    protected $autoCreateContactWidget;

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Charles.Marketing', 'marketing', 'side-menu-clients');
        $this->autoCreateContactWidget = $this->autoCreateContactFormWidget();
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
    public function onAutoCreateContact()
    {
       $this->vars['autoCreateContactWidget'] = $this->autoCreateContactWidget;
       $this->vars['clientId'] =  Client::find(post('id'))->id;
       return $this->makePartial('$/charles/marketing/controllers/clients/_auto_contact_form.htm');
    }
    protected function autoCreateContactFormWidget()
    {
        $config = $this->makeConfig('$/charles/marketing/models/client/fields_auto_create_contact.yaml');
        $config->alias = 'uploadAutoCreateContactForm';
        $config->arrayName = 'AutoCreateContact';
        $config->model = new Client;
        $widget = $this->makeWidget('Backend\Widgets\Form', $config);
        $widget->bindToController();
        return $widget;
    }
    public function onAutoCreateContactValidation()
    {
        $data = $this->autoCreateContactWidget->getSaveData();
        trace_log($data);
        


        foreach($data['names'] as $name) {
            //fonction email
            $contact = new Contact;
            $contact->name = "Service,";
            $contact->fname = "RH";
            $contact->client_id = post('id');
            $contact->email = $name.'@'.$data['email_suffix'];
            $contact->save();
            $contact->segments()->attach($data['segment']);
        }

        // Flash::info("le(s) email(s) de test sont partis ! ");

        return Redirect::refresh();
    }
}
