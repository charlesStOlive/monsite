<?php namespace Charles\Myemails\Components;

use Cms\Classes\ComponentBase;
use Validator;
use ValidationException;
use Flash;

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

        Flash::success('Message envoyé ! ');
    }
}
