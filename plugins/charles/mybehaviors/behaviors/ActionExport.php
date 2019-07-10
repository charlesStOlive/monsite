<?php namespace Charles\Mybehaviors\Behaviors;

use Backend\Classes\ControllerBehavior;
use October\Rain\Exception\ApplicationException;
use Charles\Mailgun\Models\Contact;

class ActionExport extends ControllerBehavior
{
    public function __construct($controller)
    {
        parent::__construct($controller);
    }




    public function onLoadActionExportForm()
    {
        $this->vars['keyClient'] = Contact::find(post('id'))->key;
        $this->vars['modelId'] = post('id');
        return $this->makePartial('$/charles/mybehaviors/behaviors/actionexport/_actionexport_form.htm');  
    }
}