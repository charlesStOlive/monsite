<?php namespace Charles\Mybehaviors\Behaviors;

use Backend\Classes\ControllerBehavior;
use October\Rain\Exception\ApplicationException;
use Charles\Mailgun\Models\Contact;
use Charles\Mailgun\Models\Settings;
use Twig;

class ActionExport extends ControllerBehavior
{
    public function __construct($controller)
    {
        parent::__construct($controller);
    }




    public function onLoadActionExportForm()
    {
        
        $contact = Contact::with('client')->find(post('id'));
        $messages = Settings::get('messages');
        //
        $this->vars['messages'] = $this->parseMessage($messages);
        $html_mo = Twig::parse(Settings::get('message_motivation'), compact('contact'));
        $html_li = Twig::parse(Settings::get('message_linkedin'), compact('contact'));
        $this->vars['message_motivation'] = $html_mo;
        $this->vars['message_linkedin'] = $html_li;
        $this->vars['keyClient'] = $contact->key;
        $this->vars['modelId'] = post('id');
        return $this->makePartial('$/charles/mybehaviors/behaviors/actionexport/_actionexport_form.htm');  
    }

    public function parseMessage($msgs) {
        $contact = Contact::with('client')->find(post('id'));
        $arrayMessage = new \October\Rain\Support\Collection();
        foreach ($msgs as $msg) {
            $html = Twig::parse($msg['value'], compact('contact'));
            $arrayMessage->put($msg['id'], $html );
        }
        return $arrayMessage;

    }
}