<?php namespace Charles\Marketing\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Redirect;


use Charles\marketing\Models\Competence;


use Illuminated\Wikipedia\Wikipedia;

/**
 * Competences Back-end Controller
 */
class Competences extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.ReorderController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Charles.Marketing', 'marketing', 'side-menu-competences');
    }


    public function onLoadWikipedia() {

        // $competence = Competence::first();
        // $this->launchWikiApi($competence);
            
        $competences = Competence::all();


        foreach ($competences as $competence) {

            if($competence->wiki_description) continue;

            if(!$this->launchWikiApi($competence)) {
                $this->launchWikiApi($competence,'en');
            } 
        }

        return Redirect::refresh();

    }

    public function launchWikiApi($model, $lang='fr') {

        $wiki = (new Wikipedia($lang))->page($model->name);

        if($wiki->isSuccess()) {
            $id = $wiki->getId();
            $wikiSections = $wiki->getSections();
            $wikiSectionTabs  = $wikiSections[0];
            $body = str_limit($wikiSectionTabs->getBody(),500);

            
            //
            $model->error_wiki = false;
            $model->external_link = "https://$lang.wikipedia.org/?curid=".$id;
            $model->wiki_description = $body;
            $model->save();
            return true;
        } else {
            $model->error_wiki = true;
            $model->save();
            return false;
        }

    }



}
