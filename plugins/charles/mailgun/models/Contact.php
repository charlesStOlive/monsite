<?php namespace Charles\Mailgun\Models;

use Model;

/**
 * Contact Model
 */
class Contact extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'charles_mailgun_contacts';

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
        'target' => ['Charles\Marketing\Models\Target'],
    ];
    public $belongsToMany = [
        'projects' => [
            'Charles\Marketing\Models\Project',
            'table' => 'charles_mailgun_contact_project',
        ],
        'missions' => [
            'Charles\Marketing\Models\Mission',
            'table' => 'charles_mailgun_contact_mission',
        ],
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
