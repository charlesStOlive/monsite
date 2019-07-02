<?php namespace Charles\Mailgun\Models;

use Model;

/**
 * Visit Model
 */
class Visit extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'charles_mailgun_visits';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['type'];

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
