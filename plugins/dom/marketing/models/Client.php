<?php namespace Dom\Marketing\Models;

use Model;
use \October\Rain\Database\Traits\Sluggable;

/**
 * Model
 */
class Client extends Model
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
    public $table = 'dom_marketing_clients';

    public $belongsToMany =[
        'sectors' =>[
            'Dom\Marketing\Models\Sector',
            'table' => 'dom_marketing_client_sector',
            'order' => 'name'
        ]
    ];

    public $attachOne = [ 
    'logo' => 'System\Models\File',
    ];

    public $hasMany = [
    	'projects' => 'Dom\Marketing\Models\Project',
    ];


    public function scopeListFrontEnd($query, $options = []) {
        extract(array_merge([
            'page' => 1,
            'perPage' => 10,
            'sort' => 'created_at desc',
            'sectors' => null,
        ], $options));

        if($sectors !== null) {

            if(!is_array($sectors)){
                $sectors = [$sectors];
            }

            foreach ($sectors as $sector){
                $query->whereHas('sectors', function($q) use ($sector){
                    $q->where('id', '=', $sector);
                });
            }

        }

        return $query->paginate($perPage, $page);
    }

}
