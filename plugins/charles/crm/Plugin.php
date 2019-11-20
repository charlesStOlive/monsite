<?php namespace Charles\Crm;

use Backend;
use System\Classes\PluginBase;

/**
 * Crm Plugin Information File
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
            'name'        => 'Crm',
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
        return [
            'Charles\Crm\Components\RegionList' => 'regionList',
            'Charles\Crm\Components\RegionPreview' => 'regionPreview',
            'Charles\Crm\Components\SaleList' => 'saleList',
            'Charles\Crm\Components\SalePreview' => 'salePreview'
        ]; // Remove this line to activate
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
                'tab' => 'Crm',
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
                'label'       => 'Crm',
                'url'         => Backend::url('charles/crm/regions'),
                'icon'        => 'icon-cubes',
                'permissions' => ['charles.crm.*'],
                'order'       => 3,

                'sideMenu' => [
                    'side-menu-regions' => [
                        'label'       => 'Regions',
                        'icon'        => 'icon-map',
                        'url'         => Backend::url('charles/crm/regions'),
                    ],

                    'side-menu-commercials' => [
                        'label'       => 'Commerciaux',
                        'icon'        => 'icon-users',
                        'url'         => Backend::url('charles/crm/commercials'),
                    ],
                    'side-menu-clients' => [
                        'label'       => 'Clients',
                        'icon'        => 'icon-users',
                        'url'         => Backend::url('charles/crm/clients'),
                    ],
                    'side-menu-sales' => [
                        'label'       => 'Ventes',
                        'icon'        => 'icon-money',
                        'url'         => Backend::url('charles/crm/sales'),
                    ],
                    'side-menu-gammes' => [
                        'label'       => 'Gammes',
                        'icon'        => 'icon-dot-circle-o',
                        'url'         => Backend::url('charles/crm/gammes'),
                    ],
                ],
            ],
        ];
    }
}
