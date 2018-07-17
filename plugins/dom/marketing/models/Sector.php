<?php namespace Dom\Marketing\Models;

use Model;

/**
 * Model
 */
class Sector extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'dom_marketing_sectors';

    public $belongsToMany =[
        'clients' =>[
            'Dom\Marketing\Models\Client',
            'table' => 'dom_marketing_client_sector',
            'order' => 'name'
        ],
        'projects' =>[
            'Dom\Marketing\Models\Project',
            'table' => 'dom_marketing_project_sector',
            'order' => 'name'
        ]
    ];


}
