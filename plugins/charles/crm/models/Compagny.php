<?php namespace Charles\Crm\Models;

use Model;

/**
 * Compagny Model
 */
class Compagny extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'charles_crm_compagnies';

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
         'contact' => ['Charles\Crm\Models\Contact']

    ];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
