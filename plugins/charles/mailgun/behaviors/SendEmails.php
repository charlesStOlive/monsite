<?php namespace Charles\Mailgun\Behaviors;
date_default_timezone_set("Europe/Helsinki");

use Backend\Classes\ControllerBehavior;

use Charles\Mailgun\Models\Contact;
use Charles\Mailgun\Models\Campaign;
use Charles\Marketing\Models\Moa;
use Charles\Marketing\Models\Project;
use Charles\Marketing\Models\Settings;

use Flash;
use Redirect;
use Db;
use View;
use Mail;
use Session;
use Illuminate\Database\QueryException;


class SendEmails extends ControllerBehavior
{
    public $model;
    protected $sendEmailTestWidget;
    protected $sendEmailUniqueWidget;
    protected $sendEmailGroupeWidget;
    protected $campaignId;

    public function __construct($controller)
    {
        parent::__construct($controller);
        $this->sendEmailTestWidget = $this->createSendEmailTestFormWidget();
        $this->sendEmailUniqueWidget = $this->createSendEmailUniqueFormWidget();
        $this->sendEmailGroupeWidget = $this->createSendEmailGroupeFormWidget();
    }

    // Envoi d'email de test (3)
    // onLoadSendEmailTestForm, createSendEmailTestFormWidget, onSendEmailTestValidation
    public function onLoadSendEmailTestForm()
    {
       $this->vars['sendEmailTestWidget'] = $this->sendEmailTestWidget;
       $thisCampaign = Campaign::find(post('id'))->toArray();
       $this->vars['thisCampaign'] = $thisCampaign;

        return $this->makePartial('$/charles/mailgun/behaviors/sendemails/_send_email_test_form.htm');
    }

    public function onLoadSendEmailForm()
    {
       $thisCampaign = Campaign::find(post('id'));
        $this->vars['contacts'] = $thisCampaign->getContactsEligiblesAttribute();
        $this->vars['thisCampaign'] = $thisCampaign->toArray();
        $this->campaignId = $thisCampaign->id;

       return $this->makePartial('$/charles/mailgun/behaviors/sendemails/_send_email_form.htm');
    }

    public function onLoadSendGroupeEmail()
    {
        trace_log("onLoadSendGroupeEmail");
        
        $contactsChecked = post('checked');
        trace_log($contactsChecked);
        Session::push('email.contacts', $contactsChecked);
        $this->vars['sendEmailGroupeWidget'] = $this->sendEmailGroupeWidget;
        return $this->makePartial('$/charles/mailgun/behaviors/sendemails/_send_email_groupe_form.htm');
    }

    public function onLoadSendEmailUniqueForm() {
        $this->vars['sendEmailUniqueWidget'] = $this->sendEmailUniqueWidget;
        $this->vars['thisContact'] =  Contact::find(post('id'))->toArray();
        return $this->makePartial('$/charles/mailgun/behaviors/sendemails/_send_email_unique_form.htm');
    }

    protected function createSendEmailTestFormWidget()
    {
        $config = $this->makeConfig('$/charles/mailgun/models/campaign/fields_send_email_test.yaml');
        $config->alias = 'uploadSendEmailTestForm';
        $config->arrayName = 'UploadSendEmailTest';
        $config->model = new Campaign;
        $widget = $this->makeWidget('Backend\Widgets\Form', $config);
        $widget->bindToController();
        return $widget;
    }

    protected function createSendEmailUniqueFormWidget()
    {
        $config = $this->makeConfig('$/charles/mailgun/models/campaign/fields_send_email_unique.yaml');
        $config->alias = 'uploadSendEmailUniqueForm';
        $config->arrayName = 'UploadSendEmailUnique';
        $config->model = new Campaign;
        $widget = $this->makeWidget('Backend\Widgets\Form', $config);
        $widget->bindToController();
        return $widget;
    }
    protected function createSendEmailGroupeFormWidget()
    {
        $config = $this->makeConfig('$/charles/mailgun/models/campaign/fields_send_email_groupe.yaml');
        $config->alias = 'uploadSendEmailGroupeForm';
        $config->arrayName = 'UploadSendEmailGroupe';
        $config->model = new Campaign;
        $widget = $this->makeWidget('Backend\Widgets\Form', $config);
        $widget->bindToController();
        return $widget;
    }

    public function onSendEmailTestValidation()
    {
        $data = $this->sendEmailTestWidget->getSaveData();
        $dataCampaign = Campaign::with('picture')->find(post('id'))->toArray();
        $emailTest = $data['emailTest'];


        foreach($data['contacts'] as $idContact) {
            //fonction email
            $this->sendEmail($idContact, $dataCampaign, $emailTest);
        }

        Flash::info("le(s) email(s) de test sont partis ! ");

        return Redirect::refresh();
    }

     // Envoi d'une campagne d'emailings (2)
    // onLoadSendEmailForm, onSendEmailValidation
    

    public function onSendEmailValidation($campaignId)
    {
        $campaign = Campaign::with('picture')->find($campaignId);
        $contacts = $campaign->getContactsEligiblesAttribute();
        $dataCampaign = $campaign->toArray();

        //boucle sur les contacts eligibles. 
        foreach($contacts['eligibles'] as $contact) {


            //sécurtier pour verifier si campagne d'éjà envoyé. 
            $dejaEnvoye = $contact->results()->where('campaign_id', $campaignId)->count();

            if(!$dejaEnvoye) {
                $this->assign_campaign($contact, $campaign);

                $this->sendEmail($contact->id, $dataCampaign);
               
                $campaign->increment('nb_email_sent');
            }  
       }

        $campaign->status_id = 2;
        $campaign->sent_at = \Carbon\Carbon::now();
        $campaign->save();

        Flash::info("Le(s) email(s) ont bien été envoyés ! ");
            
       return Redirect::refresh();
          
    }

    //reenvoyer email
    

    

    public function onSendEmailUniqueValidation()
    {

        $data = $this->sendEmailUniqueWidget->getSaveData();
        $idContact = post('id');
        $dataCampaign = Campaign::find($data['sent_campaign']);

        $this->sendEmail($idContact, $dataCampaign);

        Flash::info("L'email  a bien été envoyé ");
        return Redirect::refresh();
    }

    public function onSendEmailGroupeValidation()
    {
        $contactsID = Session::pull('email.contacts');
        //je supprime le premier array je ne sais pas ce qu'il fait la...
        $contactsID = $contactsID[0];
        //
        $data = $this->sendEmailGroupeWidget->getSaveData();
        $dataCampaign = Campaign::find($data['active_campaign']);
        //
        //
        foreach($contactsID as $id) {
            $this->sendEmail($id, $dataCampaign); 
        }
        Flash::info("les emails on bien été envoyés");
        return Redirect::refresh();
    }



    public function sendEmail($idContact, $dataCampaign, $testEmail=null) {
        $contact = Contact::with('region')->find($idContact);
        //création du array data email
        $dataEmail = [];
        $settings = Settings::instance()->value;
        $dataEmail['base_color'] = $settings['base_color'];
        if($contact->client) {
            $dataEmail['base_color'] = $contact->client->base_color;
        }
        $dataEmail['contact_environement'] = $contact->contactEnvironement;
        //$dataEmail['base_color'] = $contact->client->base_color;
        $dataEmail['contact'] = $contact->toArray();
        $dataEmail['target'] = $contact->target()->get(['name', 'slug'])->toArray();
        //
        if($contact->projects()->count()) {
            $dataEmail['projects'] = $contact->projects()->get(['name', 'slug', 'accroche'])->toArray();
        } else {
            $dataEmail['projects'] = Project::whereIn('id', array(2, 6, 8))->get(['name', 'slug', 'accroche'])->toArray();
        }
        if($contact->moas()->count()) {
            $dataEmail['moas'] = $contact->moas()->get(['name', 'slug', 'accroche'])->toArray();
        } else {
            $dataEmail['moas'] = Moa::whereIn('id', array(4, 7, 8))->get(['name', 'slug', 'accroche'])->toArray();
        }
        $compostings = new \October\Rain\Support\Collection();
        foreach ($contact->cloudis as $cloudi) {
            $compostings->put($cloudi->name, $cloudi->pivot->url );
        }
        $dataEmail['compostings'] = $compostings;
        $dataEmail['base_url_ctoa'] = getenv('URL_VUE');

        $myMessages = [];
        foreach($dataCampaign['messages'] as $msg) {
            if (!$contact->strict && $msg['value-t']) {
                $msg['value'] = $msg['value-t'];
            } 
            $myMessages[$msg['code']] = $msg['value'];
        }
        trace_log($myMessages);


        $dataEmail['content'] =  $myMessages;

        //$dataEmail['url_cv'] = 'app/media/cv/'.$contact->cv_name.'.pdf';
        //Affectation sujet, cible etc. 
        $subject = $dataCampaign['subject'];
        $email = $contact->email;
        $isTest = false;
        
        //Modification si c'est un test
        if($testEmail) {
            $email = $testEmail;
            //$subject = '[TEST]' . $subject;
            $isTest = true;
        }
        $html = View::make($dataCampaign['template'], $dataEmail)->render();

        Mail::raw(['html' => $html], function ($message) use($dataCampaign, $email, $subject, $contact, $isTest ) {
            $message->to($email);
            $message->subject($subject);
            $message->from('charles.stolive@gmail.com', 'Charles Saint-Olive');
            //$message->attach(storage_path('app/media/cv/'.$contact->cv_name.'.pdf'));
            if(!$isTest) {
            //Si ce n'est pas un test on met les headers. 
                $headers = $message->getHeaders();
                $headers->addTextHeader('X-Mailgun-Variables', '{"email": "'. $contact->email . '", ' .'"campaign_id": "' . $dataCampaign['id'] . '"}');
            }
        });
    }



    public function assign_campaign($contact,$campaign) {
        $contact = Contact::find($contact->id);
        try {
            $contact->campaigns()->attach($campaign->id, ['result_type' => 'waiting', 'mg_timestamp' => \Carbon\Carbon::now(), 'email' => $contact->email]);
        } catch(QueryException $e) {

            trace_log("IL Y A UNE DUPLICATION : ".$campaign->id.'/'.$contact->email );
            if (preg_match('/Duplicate entry/',$e->getMessage())){
                $contact->campaigns()->updateExistingPivot($campaign->id, ['result_type' => 'waiting', 'mg_timestamp' => \Carbon\Carbon::now(), 'email' => $contact->email]);
            } else {
                throw new ApplicationException('Erreur assign_campaign, UPDATE PIVOT ne fonctionne pas');
            }
        }
    }



}