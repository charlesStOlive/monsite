<?php namespace Charles\Crm\Models;

use Model;

/**
 * Contact Model
 */
class Contact extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'charles_crm_contacts';

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
    public $hasMany = [];
    public $belongsTo = [
        'compagny' => ['Charles\Crm\Models\Compagny']
        

        ];
    public $belongsToMany = [
        'tags' => [
            'Charles\Crm\Models\tag',
            'table' => 'charles_crm_contact_tag',
            ],
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
