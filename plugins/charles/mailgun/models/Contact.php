<?php namespace Charles\Mailgun\Models;

use Charles\Marketing\Models\Client;
use Model;
use Validator;
use Storage;
use Redirect;

/**
 * Contact Model
 */
class Contact extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [
        'name'                  => 'required',
        'fname'                 => 'required',
    ];
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

    protected $jsonable = ['message_perso'];

    protected $casts = [
        'nameFname' => 'string',
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [

    ];
    public $hasMany = [];
    public $belongsTo = [
        'target' => ['Charles\Marketing\Models\Target'],
        'client' => ['Charles\Marketing\Models\Client'],
        'region' => ['Charles\Crm\Models\Region'],
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
        'moas' => [
            'Charles\Marketing\Models\Moa',
            'table' => 'charles_mailgun_contact_moa',
        ],
        'segments' => [
            'Charles\Mailgun\Models\Segment',
            'table' => 'charles_mailgun_contact_segment',
        ],
        'cloudis' => [
            'Charles\Mailgun\Models\Cloudi',
            'table' => 'charles_mailgun_cloudi_contact',
            'pivot' => ['url', 'url_ready']
        ],
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    /**
     * MODEL EVENT
     */
    public function afterSave()
    {
        if($this->client_id == 999999) {
            trace_log("Création d'un client");
            trace_log($this->id);
            $this->client_id = null;
            return Redirect::to('/backend/charles/marketing/clients');

        } 
        
       
    }

    

    /**
     * OPTIONS
     */
    public function getClientIdOptions() {
        
        $list = Client::orderBy('name')->lists('name', 'id');
        $options = new \October\Rain\Support\Collection($list);
        $options->put(999999, 'créer une sciété');
        trace_log($options);
        return $options;
    }

    /*
    ** SCOPE
    */

    public function scopeSegmentFilter($query, $filtered)
    {
         return $query->whereHas('segments', function($q) use ($filtered) {
            $q->whereIn('id', $filtered);
        });
    }

    /**
    * GETTERS
    **/
    public function getEligibleAttribute() {
        return true;
    }
    public function getCvNameAttribute() {

        return 'cv_charles_saint-olive_'.Contact::find($this->id)->client['slug'].'_C'.$this->id;
    }
    public function getCvExisteAttribute() {

        return Storage::exists('media/cv/'.$this->cv_name.'.pdf');
    }

}
