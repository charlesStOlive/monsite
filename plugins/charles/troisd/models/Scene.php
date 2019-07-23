<?php namespace Charles\Troisd\Models;

use Model;

/**
 * Scene Model
 */
class Scene extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'charles_troisd_scenes';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    protected $jsonable = ['environements', 'options'];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        'meshs' => [
            'Charles\Troisd\Models\Mesh',
            'table' => 'charles_troisd_meshs',
        ]
    ];
    public $hasManyThrough = [
        'hps' => [
            'Charles\Crm\Models\Hp',
            'through' => 'Charles\Crm\Models\Mesh'
        ],
    ];
    public $belongsTo = [];
    public $belongsToMany = [
        'meshs' => [
            'Charles\Troisd\Models\Mesh',
            'table' => 'charles_troisd_meshs_scenes',
            'pivot' => ['position','rotation', 'scale', 'options','replace_tex','new_tex','has_instances','instance_position_from'],
            'pivotModel' => 'Charles\troisd\Models\MeshScenePivot'
        ],
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
