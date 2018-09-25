<?php namespace Charles\Marketing\Models;

use Model;

/**
 * Expertise Model
 */
class Expertise extends Model
{
    use \Charles\Mybehaviors\Classes\Traits\Icones;
    /**
     * @var string The database table used by the model.
     */
    public $table = 'charles_marketing_expertises';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [
        'competences' => [
            'Charles\Marketing\Models\Competence',
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
    public $attachMany = [];
}
