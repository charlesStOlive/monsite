<?php namespace Charles\Marketing\Models;

use Model;
use \ToughDeveloper\ImageResizer\Classes\Image;
use Config;
use Cloudder;

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
    public function getCloudiLogoIdAttribute() {
        return "client_logo_".$this->slug;
    }
    public function getCloudiLogoExisteAttribute() {
        $url = Cloudder::secureShow($this->CloudiLogoId);
        $existe = true;
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_NOBODY, true);

        //  Get the HTML or whatever is linked in $url. 
        $response = curl_exec($handle);

        /* Check for 404 (file not found). */
        return curl_getinfo($handle, CURLINFO_HTTP_CODE);
        // if($httpCode == 404) {
        //     $existe = false;
        // }

        // curl_close($handle);
        // return $existe;
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
