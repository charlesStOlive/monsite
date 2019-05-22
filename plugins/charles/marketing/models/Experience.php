<?php namespace Charles\Marketing\Models;

use Model;

/**
 * experience Model
 */
class Experience extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'charles_marketing_experiences';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    protected $jsonable = ['description'];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [
        'competences' => [
            'Charles\Marketing\Models\Competence',
            'table' => 'charles_marketing_experience_competence',
        ],
        'projects' => [
            'Charles\Marketing\Models\Project',
            'table' => 'charles_marketing_experience_project',
        ],
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
