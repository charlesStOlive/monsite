<?php namespace Charles\Mailgun\FormWidgets;

use Backend\Classes\FormWidgetBase;
use Event;
/**
 * TotalBoard Form Widget
 */
class TotalBoard extends FormWidgetBase
{
    /**
     * @inheritDoc
     */
    protected $defaultAlias = 'charles_crm_total_board';
  /**
     * @inheritDoc
     */

   public function init()
    {
        
        $this->fillFromConfig([
            'modelcible',
            'bool',
            'num',
            'fields',
        ]);
    }


    /**
     * @inheritDoc
     */
    public function render()
    {
        $this->bindToController();
        $this->prepareVars();
        return $this->makePartial('bloc');
    }

    /**
     * Prepares the form widget view data
     */
    public function prepareVars()
    {
        //$this->vars['name'] = $this->formField->getName();
        $this->vars['value'] = $this->getLoadValue();
        //$cible =   $this->config->modelcible;
        $fields = $this->config->fields;
        $fieldTransmit = [];
        foreach ($fields as $key => $field) {
        	$attribut = key($field);
            $fieldTransmit[$key]['value'] = $this->model->$attribut;
            $fieldTransmit[$key]['label'] = $field[$attribut]['label'];
            $fieldTransmit[$key]['icon'] = $field[$attribut]['icon'];
            $fieldTransmit[$key]['slabel'] = $field[$attribut]['slabel'];
        }
         

        $this->vars['fields'] = $fieldTransmit;
    }


    /**
     * @inheritDoc
     */
    public function loadAssets()
    {
        $this->addCss('css/totalboard.css', 'charles.mailgun');
        $this->addJs('js/totalboard.js', 'charles.mailgun');
    }

    /**
     * @inheritDoc
     */
    public function getSaveValue($value)
    {
          return \Backend\Classes\FormField::NO_SAVE_DATA;
    }

    public function onUpdate() {
        $this->prepareVars();
        return ['#'.$this->getId('container') => $this->makePartial('totalboard')];

    }
}
