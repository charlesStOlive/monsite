<?php namespace Charles\Crm;

use Backend;
use System\Classes\PluginBase;

/**
 * crm Plugin Information File
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
            'name'        => 'crm',
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
            'Charles\Crm\Components\MyComponent' => 'myComponent',
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
            'charles.crm.some_permission' => [
                'tab' => 'crm',
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
            'crm' => [
                'label'       => 'crm',
                'url'         => Backend::url('charles/crm/contacts'),
                'icon'        => 'icon-group',
                'permissions' => ['charles.crm.*'],
                'order'       => 500,
            ],
        ];
    }
}
