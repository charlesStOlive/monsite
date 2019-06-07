<?php namespace Charles\Crm\Models;

use Model;

/**
 * client Model
 */
class Client extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'charles_crm_clients';

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
        'sales' => ['Charles\Crm\Models\Sale'],
    ];
    public $belongsTo = [
        'commercial' => ['Charles\Crm\Models\Commercial'],
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    /**
     * GETTERS
     */
    public function getSalesSumAttribute()
    {
        return number_format($this::sales()->sum('amount')). '€';
    }
}
