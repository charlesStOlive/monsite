<?php namespace Charles\Mybehaviors\FormWidgets;

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
    protected $defaultAlias = 'dom_crm_total_board';
  /**
     * @inheritDoc
     */
    
   public $template ="totalboard_h";

   public $fields;

   public function init()
    {
        
        $this->fillFromConfig([
            'fields',
            'template',
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
        $this->vars['value'] = $this->getLoadValue();
        $this->vars['template'] = $this->template;

        $fieldTransmit = [];
        foreach ($this->fields as $key => $field) {
        	$attribut = key($field);
            $fieldTransmit[$key]['value'] = $this->model->$attribut;
            $fieldTransmit[$key]['label'] = $field[$attribut]['label'];
            $fieldTransmit[$key]['icon'] = $field[$attribut]['icon'];
            $fieldTransmit[$key]['slabel'] = $field[$attribut]['slabel'];
        }
         

        $this->vars['fields'] = $fieldTransmit;

        // $this->vars['totalPrice'] = $this->model[$cible]->where('option', 0)->sum('total');
        // $this->vars['totalCost'] = $this->model[$cible]->where('option', 1)->sum('total');
        // $this->vars['totalPostes'] = $this->model[$cible]->sum('total');

    }


    /**
     * @inheritDoc
     */
    public function loadAssets()
    {
        $this->addCss('css/totalboard.css', 'dom.crm');
        $this->addJs('js/totalboard.js', 'dom.crm');
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
        return ['#'.$this->getId('container') => $this->makePartial($this->template)];

    }
}
