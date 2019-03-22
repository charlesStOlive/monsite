<?php namespace Charles\Mailgun\Behaviors;
date_default_timezone_set("Europe/Helsinki");

use Backend\Classes\ControllerBehavior;

use Charles\Mailgun\Models\Contact;
use Charles\Folies\Models\Gamme;
use Charles\Mailgun\Models\Campaign;
use Charles\Folies\Models\Settings;

use Flash;
use Redirect;
use Db;
use View;
use Mail;
use Illuminate\Database\QueryException;


class SendEmails extends ControllerBehavior
{
    public $model;
    protected $sendEmailTestWidget;
    protected $sendEmailUniqueWidget;
    protected $campaignId;

    public function __construct($controller)
    {
        parent::__construct($controller);
        $this->sendEmailTestWidget = $this->createSendEmailTestFormWidget();
        $this->sendEmailUniqueWidget = $this->createSendEmailUniqueFormWidget();
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

    public function onSendEmailTestValidation()
    {
        $data = $this->sendEmailTestWidget->getSaveData();
        trace_log($data);
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
        $dataCampaign = Campaign::with('picture')->find($data['sent_campaign']);

        trace_log($data['sent_campaign']);

        $this->assign_campaign(Contact::find($idContact), $dataCampaign);

        $this->sendEmail($idContact, $dataCampaign);

        Flash::info("L'email de relance a bien été envoyé ");
        return Redirect::refresh();
    }



    public function sendEmail($idContact, $dataCampaign, $testEmail=null) {
        $contact = Contact::find($idContact);
        //création du array data email
        $dataEmail = [];
        $dataEmail['contact'] = $contact->toArray();
        //Affectation sujet, cible etc. 
        $subject = $dataCampaign['subject'];
        $email = $contact->email;
        $isTest = false;
        //Modification si c'est un test
        if($testEmail) {
            $email = $testEmail;
            $subject = '[TEST]' . $subject;
            $isTest = true;
        }

        $html = View::make('dom.mailgun::contact', $dataEmail)->render();

        Mail::raw(['html' => $html], function ($message) use($dataCampaign, $email, $subject, $contact, $isTest ) {
            $message->to($email);
            $message->subject($subject);
            $message->from('embauche@charles-saint-olive.com', 'Charles Saint-Olive');
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