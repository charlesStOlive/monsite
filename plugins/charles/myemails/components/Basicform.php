<?php namespace Charles\Myemails\Components;

use Cms\Classes\ComponentBase;
use Validator;
use ValidationException;
use Flash;

use Mail;
// use Illuminated\Wikipedia\Wikipedia;

    


class Basicform extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'basicform Component',
            'description' => 'Simple composant'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        // $wikiSections = (new Wikipedia)->page('php')->getSections();
        // $wikiSectionTabs  = $wikiSections[0]; 
        // trace_log($wikiSectionTabs->getImages()[0]->getUrl());
        // trace_log($wikiSectionTabs->getTitle());
        // trace_log(str_limit($wikiSectionTabs->getBody(),200));



        // foreach($wikiSectionTabs as $key => $value ) {
        //     trace_log($key);
        //     trace_log($value);
        //  }
        //$intro = str_limit($wiki->getSections(), 200);
        //trace_log($wiki[0][2]);

        $this->addJs('assets/js/control.js');
    }

    public function onSend() {
        $data;
        $data = post();

        $rules = [
            'name' => 'required',
            'email' => 'required|email',
        ];

        $validation = Validator::make($data, $rules);

        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        trace_log("data");
        trace_log($data['email']);

        Mail::send('front::mail.contact', $data, function($message) use ($data) {
            $message->to($data['email']);
        });

        Flash::success('Message envoyé ! ');
    }
}
