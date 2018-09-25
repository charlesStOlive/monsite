<?php namespace Charles\Mybehaviors;

use Backend;
use System\Classes\PluginBase;

/**
 * mybehaviors Plugin Information File
 */
class Plugin extends PluginBase
{

    public $require = [
        'RainLab.User',
        'Renatio.DynamicPDF',
    ];

    
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'mybehaviors',
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
        return []; // Remove this line to activate

        return [
            'Charles\Mybehaviors\Components\MyComponent' => 'myComponent',
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
            'charles.mybehaviors.some_permission' => [
                'tab' => 'mybehaviors',
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
            'mybehaviors' => [
                'label'       => 'mybehaviors',
                'url'         => Backend::url('charles/mybehaviors/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['charles.mybehaviors.*'],
                'order'       => 500,
            ],
        ];
    }
}
