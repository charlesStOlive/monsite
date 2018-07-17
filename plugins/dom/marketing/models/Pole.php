<?php namespace Dom\Marketing\Models;

use Model;

/**
 * Model
 */
class Pole extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'dom_marketing_pole';

    public $belongsToMany =[
        'projects' =>[
            'Dom\Marketing\Models\Project',
            'table' => 'dom_marketing_project_pole',
            'order' => 'name'
        ]
    ];
}
