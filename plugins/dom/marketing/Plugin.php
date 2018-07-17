<?php namespace Dom\Marketing;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'Dom\Marketing\Components\Projects' => 'projects',
            'Dom\Marketing\Components\Contacts' => 'Contacts'
//            'Dom\Marketing\Components\FilterProjects' => 'filterprojects',
        ];
    }

    public function registerSettings()
    {
    }
}
