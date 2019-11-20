<?php namespace Charles\Mailgun;

use Backend;
use System\Classes\PluginBase;
use Event;

use Charles\Mailgun\Models\Campaign;


/**
 * Mailgun Plugin Information File
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
            'name'        => 'Mailgun',
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

    public function registerSchedule($schedule)
    {
        $schedule->call(function () {
            $campagnesAnalys = Campaign::where('status_id',2)->first();
            
            if($campagnesAnalys) {
                trace_log("Campagne premier retour à  analyser : " .$campagnesAnalys->name);
            }

            
            if($campagnesAnalys) {
                trace_log("Campagne second retour à  analyser : " .$campagnesAnalys->name);
            }

        })->everyMinute();
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        \Charles\Marketing\Models\Client::extend(function($model){
                $model->belongsTo['cloudi'] = ['Charles\Mailgun\Models\Cloudi'];
        });

        // Extend all backend form usage
        Event::listen('backend.form.extendFields', function($widget) {

            //Only for the User controller
            if (!$widget->getController() instanceof \Charles\Marketing\Controllers\Clients) {
                return;
            }

            // Only for the User model
            if (!$widget->model instanceof \Charles\Marketing\Models\Client) {
                return;
            }

            // Add an extra cloudi field
            $widget->addTabFields([
                'cloudi' => [
                    'tab' => 'Cloudinaries',
                    'label'   => 'Campagne de base',
                    'type'    => 'relation',
                    'placeholder' => '--choisissez une campagne--'
                ]
            ]);
        });

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
            'Charles\Mailgun\Components\MyComponent' => 'myComponent',
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
            'charles.mailgun.*' => [
                'tab' => 'Campagnes',
                'label' => 'Gerer les campagnes'
            ],
        ];
    }


    public function registerFormWidgets()
    {
        return [
            'Charles\Mailgun\FormWidgets\TotalBoard' => 'totalboard',
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
            'mailgun' => [
                'label'       => 'Campagnes',
                'url'         => Backend::url('charles/mailgun/contacts'),
                'icon'        => 'icon-envelope',
                'permissions' => ['charles.mailgun.*'],
                'order'       => 1,

                'sideMenu' => [
                        'side-menu-campaigns' => [
                            'label'       => 'Campagnes',
                            'icon'        => 'icon-envelope',
                            'url'         => Backend::url('charles/mailgun/campaigns'),
                        ],

                        'side-menu-contacts' => [
                            'label'       => 'Contacts',
                            'icon'        => 'icon-users',
                            'url'         => Backend::url('charles/mailgun/contacts'),
                        ],
                        'side-menu-segments' => [
                            'label'       => 'Segments',
                            'icon'        => 'icon-dot-circle-o',
                            'url'         => Backend::url('charles/mailgun/segments'),
                        ],
                        'side-menu-cloudis' => [
                            'label'       => 'Cloudinaries',
                            'icon'        => 'icon-cloud',
                            'url'         => Backend::url('charles/mailgun/cloudis'),
                        ],
                ],
            ],
        ];
    }
    public function registerSettings()
    {
        return [
            'mailgun_settings' => [
                'label'       => 'Options campagnes',
                'description' => 'id de tests',
                'category'    => 'Mes Options',
                'icon'        => 'icon-cog',
                'class'       => 'Charles\Mailgun\Models\Settings',
                'order'       => 2
                // 'keywords'    => 'security location',
                // 'permissions' => ['acme.users.access_settings']
            ]
        ];
    }
}
