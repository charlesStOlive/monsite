<?php  namespace Dom\Marketing\Components;

use Cms\Classes\ComponentBase;
use Dom\Marketing\Models\Project;

class Projects extends ComponentBase
{
    public function componentDetails() {
        return [
            'name' => 'Liste des projets (flex)',
            'description' => 'Affiche la liste de tous les projets'
        ];
    }

    public function defineProperties() {
        return [
            'results' => [
                'title' => 'Nombre de projets',
                'description' => 'Combien voulez-vous afficher de projets simultanément ?',
                'default' => 9,
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Veuillez saisir uniquement des nombres'
            ],

            'sortOrder' => [
                'title' => 'Ordre de tri',
                'description' => 'Défini dans quel ordre afficher les projets',
                'type' => 'dropdown',
                'default' => 'year desc'
            ]
        ];
    }

    public function getSortOrderOptions() {
        return [
            'year asc' => 'Année (croissante)',
            'year desc' => 'Année (décroissante)'
        ];
    }

    public function onRun() {
        $this->projects = $this->loadProjects();
    }

    public function loadProjects () {

        $query = Project::all();

        if ($this->property('sortOrder') == 'year asc') {
            $query = $query->sortBy('year');
        } else if ($this->property('sortOrder') == 'year desc') {
            $query = $query->sortByDesc('year');

        }

        if ($this->property('results') > 0) {
            $query = $query->take($this->property('results'));
        }

        return $query;
    }

    public $projects;
}