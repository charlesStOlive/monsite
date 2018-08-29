<?php namespace Charles\Marketing\Components;

use Cms\Classes\ComponentBase;
use ApplicationException;


use Charles\Marketing\Models\Salaire;

class Todo extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'Todo List',
            'description' => 'Implements a simple to-do list.'
        ];
    }

    public function defineProperties()
    {
        return [
            'max' => [
                'description'       => 'The most amount of todo items allowed',
                'title'             => 'Max items',
                'default'           => 10,
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'The Max Items value is required and should be integer.'
            ]
        ];
    }

    public function onAddItem()
    {
        $amount = post('amount');
        $salaire = Salaire::where('salaire_start', '<=', $amount )->where('salaire_end', '>=', $amount )->first();

        $this->page['amount'] = $amount;
        $this->page['salaire'] = $salaire;

    }
}
