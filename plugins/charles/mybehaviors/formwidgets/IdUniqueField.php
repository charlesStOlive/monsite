<?php namespace Dom\Crm\FormWidgets;

use Backend\Classes\FormWidgetBase;
use Carbon\Carbon;

/**
 * idUniqueField Form Widget
 */
class IdUniqueField extends FormWidgetBase
{
    /**
     * @inheritDoc
     */
    protected $defaultAlias = 'dom_crm_id_unique_field';

    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->config->champsUnique = 'code';
        $this->config->champsParentUnique = 'code';

        $this->fillFromConfig([
            'champsUnique',
            'champsParentUnique',
            'champsLiaison',
        ]);

    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('iduniquefield');
    }

    /**
     * Prepares the form widget view data
     */
    public function prepareVars()
    {
        $this->vars['name'] = $this->formField->getName();
        $this->vars['model'] = $this->model;

        if(!$this->getLoadValue()) {
            $this->vars['value'] = $this->createUniqueId();
        } else {
            $this->vars['value'] = $this->getLoadValue();
        }
    }

    public function createUniqueId() {
        //récuperation de la valeur unique client
        $cible =   $this->model[$this->config->modelParent];
        dd($cible);
        $uniqueParent = $cible->unique;
        //
        $dt = Carbon::now();
        //
        /*$liaison =   $this->config->champsLiaison;
        $get_id = $this->model->attributes['client_id'];
        $count = $this->model->where($liaison, '=', $get_id)
        ->whereYear('created_at', '=', date('Y'))
        ->count();
        $count += 1;*/
        //
         $value = $uniqueParent. '-'.$dt->format('y') . sprintf("%02d", $dt->month) ."-P".sprintf("%04d", $this->model->id);
        return $value;

    }

    /**
     * @inheritDoc
     */
    public function loadAssets()
    {
        $this->addCss('css/iduniquefield.css', 'dom.crm');
        $this->addJs('js/iduniquefield.js', 'dom.crm');
    }

    /**
     * @inheritDoc
     */
    public function getSaveValue($value)
    {
        return $value;
    }
}
