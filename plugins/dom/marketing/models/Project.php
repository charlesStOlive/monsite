<?php namespace Dom\Marketing\Models;

use Model;

/**
 * Model
 */
class Project extends Model
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
    public $table = 'dom_marketing_projects';

    public $belongsTo = [
    	'client' => 'Dom\Marketing\Models\Client'
    ];

    public $attachOne = [
        'top_image' => 'System\Models\File',
        'thumbnail' => 'System\Models\File'
    ];

    public $attachMany = [
        'gallery' => 'System\Models\File'
    ];

    public $belongsToMany =[
        'jobs' =>[
            'Dom\Marketing\Models\Job',
            'table' => 'dom_marketing_project_job',
            'order' => 'name'
        ],
        'poles' => [
            'Dom\Marketing\Models\Pole',
            'table' => 'dom_marketing_project_pole',
            'order' => 'name'
        ],
    ];

    public function scopeListFrontEnd($query, $options = [])
    {
        extract(array_merge([
            'page' => 1,
            'perPage' => 9,
            'sort' => 'created_at_desc',
            'job' => '',
        ], $options));

        trace_log($options);

        if($job) {
            $query->whereHas('jobs', function($q) use ($job) {
                $q->where('id', '=', $job);
            });
        }


        return $query->paginate($perPage, $page);
    }

}
