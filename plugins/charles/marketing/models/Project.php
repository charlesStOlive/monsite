<?php namespace Charles\Marketing\Models;

use Model;

/**
 * Project Model
 */
class Project extends Model
{
    use \October\Rain\Database\Traits\Sortable;
    /**
     * @var string The database table used by the model.
     */
    public $table = 'charles_marketing_projects';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    protected $jsonable = ['video'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $belongsTo = [
        'client' => ['Charles\Marketing\Models\Client'],
    ];
    public $belongsToMany = [
        'competences' => [
            'Charles\Marketing\Models\Competence',
            'table' => 'charles_marketing_competence_project',
        ],
        'expertises' => [
            'Charles\Marketing\Models\Expertise',
            'table' => 'charles_marketing_expertises_project',
        ],
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [
        'main_picture' => ['System\Models\File'],
        'video_picture' =>['System\Models\File'],
        ];
    public $attachMany = [
        'pictures' => ['System\Models\File'],
    ];
}
