<?php namespace Charles\Mailgun\Models;

use Model;

/**
 * template Model
 */
class Template extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'charles_mailgun_templates';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['id'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];


    protected $jsonable = ['content'];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        'campaign' => ['Charles\Mailgun\Models\Campaign']
        ];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [
    'featured_image' => 'System\Models\File',
    ];
    public $attachMany = [];

    public function getContentCollectionAttribute() {
        //transformation du array content en collection
        $collection = new \October\Rain\Support\Collection($this->content);
        //avec une methode de Collection, le champ name deviens la clé et contenu la valeur.
        trace_log($collection->pluck('contenu', 'name'));
    }
}
