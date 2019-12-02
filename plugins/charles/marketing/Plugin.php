<?php namespace Charles\Marketing;

use Backend;
use System\Classes\PluginBase;

/**
 * marketing Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'marketing',
            'description' => 'No description provided yet...',
            'author'      => 'charles',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {

    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {

        return [
            'Charles\Marketing\Components\Competences' => 'myCompetences',
            'Charles\Marketing\Components\Todo' => 'demoTodo'
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {

        return [
            'charles.marketing.*' => [
                'tab' => 'Contenus',
                'label' => 'AUtoriser accès contenu'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {

        return [
            'marketing' => [
                'label'       => 'Contenus',
                'url'         => Backend::url('charles/marketing/secteurs'),
                'icon'        => 'icon-leaf',
                'permissions' => ['charles.marketing.*'],
                'order'       => 2,

                'sideMenu' => [
                        'side-menu-secteurs' => [
                            'label'       => 'Secteur',
                            'icon'        => 'icon-dot-circle-o',
                            'url'         => Backend::url('charles/marketing/secteurs'),
                        ],
                        'side-menu-experiences' => [
                            'label'       => 'Experiences',
                            'icon'        => 'icon-flag-checkered',
                            'url'         => Backend::url('charles/marketing/experiences'),
                        ],
                        'side-menu-missions' => [
                            'label'       => 'Missions',
                            'icon'        => 'icon-flag-checkered',
                            'url'         => Backend::url('charles/marketing/missions'),
                        ],
                        'side-menu-moas' => [
                            'label'       => "Maitrise d'ouvrages",
                            'icon'        => 'icon-flag-checkered',
                            'url'         => Backend::url('charles/marketing/moas'),
                        ],
                        
                        'side-menu-projects' => [
                            'label'       => 'Projets',
                            'icon'        => 'icon-folder-open',
                            'url'         => Backend::url('charles/marketing/projects'),
                        ],
                        // 'side-menu-expertises' => [
                        //     'label'       => 'Expertises',
                        //     'icon'        => 'icon-file-text-o',
                        //     'url'         => Backend::url('charles/marketing/expertises'),
                        // ],
                        'side-menu-competences' => [ 
                            'label'       => 'Competences',
                            'icon'        => 'icon-tasks',
                            'url'         => Backend::url('charles/marketing/competences'),
                        ],
                        'side-menu-targets' => [
                            'label'       => 'Cibles',
                            'icon'        => 'icon-dot-circle-o',
                            'url'         => Backend::url('charles/marketing/targets'),
                        ],
                       

                        // 'side-menu-salaires' => [ 
                        //     'label'       => 'Grille des salaires',
                        //     'icon'        => 'icon-money',
                        //     'url'         => Backend::url('charles/marketing/salaires'),
                        // ],
                    ],
                ],
        ];
    }
    public function registerSettings()
    {
        return [
            'site_settings' => [
                'label'       => 'Site option',
                'description' => 'Champs fixe du site en vue.',
                'category'    => 'Mes Options',
                'icon'        => 'icon-cog',
                'class'       => 'Charles\Marketing\Models\Settings',
                'order'       => 1
                // 'keywords'    => 'security location',
                // 'permissions' => ['acme.users.access_settings']
            ]
        ];
    }

}
