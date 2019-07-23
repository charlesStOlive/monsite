<?php namespace Charles\Troisd\Models;

use Model;

/**
 * Mesh Model
 */
class Mesh extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'charles_troisd_meshes';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    protected $jsonable = ['position', 'rotation', 'scale', 'options', 'mesh_animes'];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        'hps' => ['Charles\troisd\Models\Hp'],
    ];
    public $belongsTo = [
        'scene' => ['Charles\troisd\Models\Scene']
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function listMeshs($fieldName, $value, $formData)
    {
        return Mesh::lists('slug', 'id');
    }
}
