<?php namespace Charles\Mailgun\Behaviors;
date_default_timezone_set("Europe/Helsinki");

use Backend\Classes\ControllerBehavior;

use Charles\Mailgun\Models\Contact;
use Charles\Mailgun\Models\Campaign;

use Flash;
use Redirect;
use Db;
use View;
use Twig;
use Mail;
use Session;
use Storage;
use ApplicationException;
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
        $addPj = $data['add_pj'];

        $this->sendEmail($idContact, $dataCampaign, null, $addPj );

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



    public function sendEmail($idContact, $dataCampaign, $testEmail=null, $addPj=false) {
        $contact = Contact::with('region')->find($idContact);
        //
        if($addPj) {
            $pjExist = Storage::exists('media/cv/'.$contact->cv_name.'.pdf');
            trace_log("adresse cv : ".'cv/'.$contact->cv_name.'.pdf');
            if(!$pjExist) throw new ApplicationException('Erreur CV introuvable. affichez le une fois');
        }
        //création du array data email
        $dataEmail = [];
        $dataEmail['base_color'] = $contact->clientColor;
        $dataEmail['contact_environement'] = $contact->contactEnvironement;
        $dataEmail['contact'] = $contact->toArray();
        $dataEmail['projects'] = $contact->projectsDefault;
        $dataEmail['moas'] = $contact->moasDefault;
        $dataEmail['compostings'] = $contact->cloudisDefault;
        $dataEmail['base_url_ctoa'] = getenv('URL_VUE');
        //
        //
        $myMessages = [];
        foreach($dataCampaign['messages'] as $msg) {
            // if (!$contact->strict && $msg['value-t']) {
            //     $msg['value'] = $msg['value-t'];
            // }
            if($dataCampaign['use_personalisation'] &&  $contact->show_message_perso )  {
                $msgPerso = $this->getMessagePerso($contact->message_perso, $msg['code'] );
                if($msgPerso)  $msg['value'] = $msgPerso;
            } 
            $myMessages[$msg['code']] = Twig::parse($msg['value'], compact('contact'));
        }
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

        Mail::raw(['html' => $html], function ($message) use($dataCampaign, $email, $subject, $contact, $isTest, $addPj ) {
            $message->to($email);
            $message->subject($subject);
            $message->from('charles.stolive@gmail.com', 'Charles Saint-Olive');
            if($addPj) {
                $message->attach(storage_path('app/media/cv/'.$contact->cv_name.'.pdf')); 
            }
            //$message->attach(storage_path('app/media/cv/'.$contact->cv_name.'.pdf'));
            if(!$isTest) {
            //Si ce n'est pas un test on met les headers. 
                $headers = $message->getHeaders();
                $headers->addTextHeader('X-Mailgun-Variables', '{"email": "'. $contact->email . '", ' .'"campaign_id": "' . $dataCampaign['id'] . '"}');
            }
        });
    }

    public function getMessagePerso($msgs, $value) {
        $messageToReturn = null;
        foreach($msgs as $msg) {
            if ($msg['code'] ==  $value) {
                $messageToReturn = $msg['value'];
            }
        }
        return $messageToReturn;
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