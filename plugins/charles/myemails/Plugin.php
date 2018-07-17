<?php namespace Charles\Myemails;

use Backend;
use System\Classes\PluginBase;

/**
 * myemails Plugin Information File
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
            'name'        => 'myemails',
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
            'Charles\Myemails\Components\Basicform' => 'basicform',
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
            'charles.myemails.some_permission' => [
                'tab' => 'myemails',
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
        return []; // Remove this line to activate

        return [
            'myemails' => [
                'label'       => 'myemails',
                'url'         => Backend::url('charles/myemails/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['charles.myemails.*'],
                'order'       => 500,
            ],
        ];
    }
}
