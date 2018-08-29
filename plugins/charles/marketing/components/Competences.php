<?php namespace Charles\Marketing\Components;

use Cms\Classes\ComponentBase;

use Charles\Marketing\Models\Competencetype;

class Competences extends ComponentBase
{

    /**
     * collection des competences
     * @var collection
     */
    public $competencetypes;



    public function componentDetails()
    {
        return [
            'name'        => 'competences Component',
            'description' => 'Le composant affiche la liste des compétences.'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function init() {
        //execution à l'initialisation avec l'ajax. 
    }


    public function onRun() {
        // pas d'execution AJAX. 
        $this->competencetypes = Competencetype::all();
        
    }
}
