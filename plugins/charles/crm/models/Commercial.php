<?php namespace Charles\Crm\Models;

use Model;

/**
 * commercial Model
 */
class Commercial extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'charles_crm_commercials';

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
        'clients' => ['Charles\Crm\Models\Client'],
    ];
    public $hasManyThrough = [
        'sales' => [
            'Charles\Crm\Models\Sale',
            'through' => 'Charles\Crm\Models\Client'
        ],
    ];
    public $belongsTo = [
        'region' => ['Charles\Crm\Models\Region'],
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
