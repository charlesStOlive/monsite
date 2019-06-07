<?php namespace Charles\Crm\Models;

use Model;

/**
 * region Model
 */
class Region extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'charles_crm_regions';

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
    public $hasMany = [
        'commercials' => ['Charles\Crm\Models\Commercial'],
    ];
    public $hasManyThrough = [
        'clients' => [
            'Charles\Crm\Models\Client',
            'through' => 'Charles\Crm\Models\Commercial'
        ],
    ];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    /**
     * GETTETERS
     */
    // // Les deux premiers getters sont inutiles puisque cela peut être fait directement dans le yaml list du model. 
    public function getNbCommercialsAttribute()
    {
        return $this::commercials()->count();
    }

    public function getNbClientsAttribute()
    {
        return $this::clients()->count();
    }


     /**
      * SCOPE
      */
}
