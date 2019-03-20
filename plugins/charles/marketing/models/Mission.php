<?php namespace Charles\Marketing\Models;

use Model;

/**
 * mission Model
 */
class Mission extends Model
{
    use \October\Rain\Database\Traits\Sortable;
    /**
     * @var string The database table used by the model.
     */
    public $table = 'charles_marketing_missions';

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
            'table' => 'charles_marketing_competence_mission',
        ],
        'targets' => [
            'Charles\Marketing\Models\Target',
            'table' => 'charles_marketing_mission_target',
        ],
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
