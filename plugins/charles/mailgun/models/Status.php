<?php namespace Charles\Mailgun\Models;

use Model;

/**
 * Status Model
 */
class Status extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'charles_mailgun_statuses';

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
        'campaigns' => ['Charles\Mailgun\Models\Campaign'],
    ];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
