<?php namespace Charles\Crm\Models;

use Model;

/**
 * gamme Model
 */
class Gamme extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'charles_crm_gammes';

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
    public $hasMany = [
        'sales' => ['Charles\Crm\Models\Region'],
    ];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
