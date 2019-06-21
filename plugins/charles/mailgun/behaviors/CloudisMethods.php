<?php namespace Charles\Mailgun\Behaviors;

use Backend\Classes\ControllerBehavior;

use Charles\Marketing\Models\Client;
use Charles\Mailgun\Models\Contact;
use Charles\Mailgun\Models\Cloudi;
use Charles\Marketing\Models\Settings;
//
use Cloudder;
use Flash;
use Redirect;


class CloudisMethods extends ControllerBehavior
{

    public function __construct($controller)
    {
        parent::__construct($controller);
    }

    /**
     * LES APPELS
     */

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

    /**
     * LES METHODES
     */
    public function getColors($client=null) {
        //Gestion de la couleur.
        $settings = Settings::instance()->value;
        $colorClient = $settings['base_color'];
        if($client) {
            if($client->base_color) $colorClient = $client->base_color;
        }
        //cloudinary utilise des couleurs sans le # on l'enlève. 
        return substr($colorClient, 1);

    }
    public function createContactImage($id='null') {
        if($id == 'null') {
            $id = post('id');
        }
        $contact = Contact::find($id);
        //Suppresion des cloudis existant : 
        $contact->cloudis()->detach();
        //Client et couleurs
        $client = $contact->client;
        $colorClient = $this->getColors($client);
        //On réécupère tous les cloudis ( sauf les anciens liées à un client )
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

    public function onTestImage($id='null') {
        if($id == 'null') {
            $id = post('id');
        }
        //
        $cloudi = Cloudi::find($id);
        //
        $contact = Contact::first();
        //Client et couleurs
        $client = $contact->client;
        $colorClient = $this->getColors($client);

        $recError = false;
        $url="";
        if($cloudi->is_client_needed && !$client) {
            $recError = true;
        } else if($cloudi->is_logo_needed) {
            if(!$client->cloudiLogoExiste) $recError = true;
        } 
        trace_log("recError ".$recError);
        if(!$recError) {
            $this->vars['src'] =  Cloudder::secureShow($cloudi->path, eval($cloudi->config));
            return $this->makePartial('$/charles/marketing/controllers/clients/_img_form.htm');
        } else {
            throw new ApplicationException('Erreur sur le test');
        }
    }

}