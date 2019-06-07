<?php namespace Charles\Crm\Components;

use Initbiz\PowerComponents\Classes\ListComponentBase;

class SaleList extends ListComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'SaleList Component',
            'description' => 'Component rendering list of Sale'
        ];
    }
    
}
