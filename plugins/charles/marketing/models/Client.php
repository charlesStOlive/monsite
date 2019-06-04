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

    protected $jsonable = ['cv_option'];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        'projects' => ['Charles\Marketing\Models\Project'],
        'contacts' => ['Charles\Mailgun\Models\Contact'],
    ];
    public $belongsTo = [];
    public $belongsToMany = [
        'cloudis' => [
            'Charles\Mailgun\Models\Cloudi',
            'table' => 'charles_mailgun_cloudi_client',
            'pivot' => ['url', 'url_ready']
        ],
    ];
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

    public function listTechnical($fieldName, $value, $formData)
    {
        return Competence::CompetencetypeFilter([1,2,3,4])->lists('name', 'id');
    }
    public function listMarketing($fieldName, $value, $formData)
    {
        return Competence::CompetencetypeFilter([5])->lists('name', 'id');
    }
    public function listSoftSkills($fieldName, $value, $formData)
    {
        return Competence::CompetencetypeFilter([6])->lists('name', 'id');
    }
    public function listFonctionel($fieldName, $value, $formData)
    {
        return Competence::CompetencetypeFilter([7])->lists('name', 'id');
    }
}
