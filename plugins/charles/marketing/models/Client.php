<?php namespace Charles\Marketing\Models;

use Model;
use \ToughDeveloper\ImageResizer\Classes\Image;
use Config;

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
    public $attachMany = [];


    public function getlogoAfficheAttribute()
    {
        $mediaUrl = url(Config::get('cms.storage.media.path'));
        $image = new Image($mediaUrl.'/'.$this->logo);
        return '<img src="'.$image->resize(50, 50, [ 'mode' => 'auto' ]).'">';
    }
}
