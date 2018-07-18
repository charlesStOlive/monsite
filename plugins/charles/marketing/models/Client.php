<?php namespace Charles\Marketing\Models;

use Model;

/**
 * Client Model
 */
class Client extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'charles_marketing_clients';

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
        'projects' => ['Charles\Marketing\Models\Project'],
    ];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [
        'main_picture' => ['System\Models\File'],
        ];
    public $attachMany = [];
}
