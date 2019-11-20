<?php namespace Charles\Troisd;

use Backend;
use System\Classes\PluginBase;

/**
 * Troisd Plugin Information File
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
            'name'        => 'Troisd',
            'description' => 'No description provided yet...',
            'author'      => 'Charles',
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
        return []; // Remove this line to activate

        return [
            'Charles\Troisd\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'charles.troisd.some_permission' => [
                'tab' => 'Troisd',
                'label' => 'Some permission'
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
            'troisd' => [
                'label'       => '3D',
                'url'         => Backend::url('charles/troisd/scenes'),
                'icon'        => 'icon-cubes',
                'permissions' => ['charles.troisd.*'],
                'order'       => 10,
                'sideMenu' => [
                    'side-menu-scenes' => [
                        'label'       => 'Scenes',
                        'icon'        => 'icon-map',
                        'url'         => Backend::url('charles/troisd/scenes'),
                    ],

                    'side-menu-meshs' => [
                        'label'       => 'Objets',
                        'icon'        => 'icon-map',
                        'url'         => Backend::url('charles/troisd/meshs'),
                    ],
                    'side-menu-hps' => [
                        'label'       => 'Hot points',
                        'icon'        => 'icon-map',
                        'url'         => Backend::url('charles/troisd/hps'),
                    ],
                    'side-menu-contents' => [
                        'label'       => 'Contenus',
                        'icon'        => 'icon-map',
                        'url'         => Backend::url('charles/troisd/contents'),
                    ],
                ],
            ],
        ];
    }
}
