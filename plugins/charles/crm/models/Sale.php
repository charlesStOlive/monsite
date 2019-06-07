<?php namespace Charles\Crm\Models;

use Model;

/**
 * sale Model
 */
class Sale extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'charles_crm_sales';

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
    public $belongsTo = [
        'client' => ['Charles\Crm\Models\Client'],
        'gamme' => ['Charles\Crm\Models\Gamme'],
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
