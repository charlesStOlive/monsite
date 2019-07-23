<?php namespace Charles\Troisd\Models;

use Model;

/**
 * Content Model
 */
class Content extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'charles_troisd_contents';

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
