<?php namespace Charles\Marketing\Models;

use Model;

/**
 * target Model
 */
class Target extends Model
{
    use \October\Rain\Database\Traits\Sortable;
    /**
     * @var string The database table used by the model.
     */
    public $table = 'charles_marketing_targets';

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
        'missions' => [
                'Charles\Marketing\Models\Mission',
                'table' => 'charles_marketing_mission_target',
            ],
        ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
