<?php namespace Charles\Marketing\Models;

use Model;

/**
 * Competence Model
 */
class Competence extends Model
{
     use \October\Rain\Database\Traits\Sortable;
     use \Charles\Mybehaviors\Classes\Traits\Icones;
    /**
     * @var string The database table used by the model.
     */
    public $table = 'charles_marketing_competences';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['id'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [
        'competencetype' => ['Charles\Marketing\Models\Competencetype'],
    ];
    public $belongsToMany = [
        'expertises' => [
            'Charles\Marketing\Models\Expertise',
            'table' => 'charles_marketing_competence_expertise',
        ],
        'projects' => [
            'Charles\Marketing\Models\Project',
            'table' => 'charles_marketing_expertises_project',
        ],
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [
        'main_picture' => ['System\Models\File'],
        ];
    public $attachMany = [];

}


    