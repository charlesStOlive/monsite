<?php namespace Dom\Marketing\Components;

use Cms\Classes\ComponentBase;
use Input;
use Mail;
use Validator;
use Validation;
use Redirect;
use ValidationException;

class Contacts extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Formualaires Clients',
            'description' => 'Les formulaires liées aux clients'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onSend() {

        $data = post();

        $rules =    [
                'name' => 'required|min:3',
                'email' => 'required|email'
            ];

        $validator = Validator::make($data, $rules);



        if ($validator->fails()) {
            throw new ValidationException($validator);

        } else {
            $vars = ['name' => Input::get('name'), 'email' => Input::get('email'), 'body' => Input::get('body')];

        Mail::send('charles.clients::mail.test', $vars, function($message) {

        $message->to('admin@domain.tld', 'Admin Person');
        $message->subject('Message de test');

});

        }
        
    }
}
