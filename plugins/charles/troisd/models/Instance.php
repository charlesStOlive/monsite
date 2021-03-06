<?php namespace Charles\Troisd\Models;

use Model;

/**
 * Instance Model
 */
class Instance extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'charles_troisd_instances';

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
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
