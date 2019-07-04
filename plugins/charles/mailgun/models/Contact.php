<?php namespace Charles\Mailgun\Models;

use Charles\Marketing\Models\Client;
use Model;
use Validator;
use Storage;
use Redirect;
use Backend;
use Crypt;
use Cloudder;

use Illuminate\Contracts\Encryption\DecryptException;

/**
 * Contact Model
 */
class Contact extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [
        'name'                  => 'required',
        'fname'                 => 'required',
        'key'                 => '|unique:charles_mailgun_contacts',
    ];
    /**
     * @var string The database table used by the model.
     */
    public $table = 'charles_mailgun_contacts';

    /**
     * @var array Guarded fields
     */
    protected $guarded = [''];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['id'];

    protected $jsonable = ['message_perso'];

    protected $casts = [
        'nameFname' => 'string',
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [

    ];
    public $hasMany = [
        'visits' => ['Charles\Mailgun\Models\Visit'],
    ];
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
    public function beforeSave() {
        if(!$this->key) {
            $this->key = str_Random(12);
        }
        if(!$this->region_id) {
            $this->region_id = rand(1, 5);
        }
        

        
    }
    public function afterSave() {
            if($this->contactEnvironement == 'full')  {
                $this->createCloudis();   
        }  
    }

    

    /**
     * OPTIONS
     */
    public function getClientIdOptions() {
        
        $list = Client::orderBy('name')->lists('name', 'id');
        $options = new \October\Rain\Support\Collection(json_decode($list));
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
    public function scopeHasVisits($query, $filtered)
    {
         return $query->has('visits');
    }
    public function scopeHasVisitsFilter($query,  $filtered)
    {
        if($filtered == 2 ) return $query->has('visits');
        if($filtered == 1 ) return $query->doesnthave('visits');
    }
    public function scopeHasClientFilter($query,  $filtered)
    {
        if($filtered == 2 ) return $query->has('client');
        if($filtered == 1 ) return $query->doesnthave('client');
    }

    /**
    * GETTERS
    **/
    public function getContactEnvironementAttribute() {
        if($this->client) {
            if($this->client->logo &&  $this->client->base_color ) return "full";
            if($this->client->logo &&  !$this->client->base_color ) return "logo";
            if(!$this->client->logo &&  $this->client->base_color ) return "color";
        }
        return null;
    }
    public function getEligibleAttribute() {
        return true;
    }
    public function getCvNameAttribute() {

        return 'cv_charles_saint-olive_'.Contact::find($this->id)->client['slug'].'_C'.$this->id;
    }
    public function getCvExisteAttribute() {

        return Storage::exists('media/cv/'.$this->cv_name.'.pdf');
    }
    public function getKeyEncryptedAttribute() {

        $key = $this->id;
        return Crypt::encrypt($key);
    }
    /**
     * SETTERS
     */
    
     /**
      * METHODS
      */
      public function createCloudis() {
        if($this->contactEnvironement != 'full') throw new ApplicationException('Environement client pas prêt !');
        $this->cloudis()->detach();
        //Client et couleurs
        $contact = $this;
        $client = $this->client;
        $colorClient = substr($client->base_color, 1);
        //On réécupère tous les cloudis ( sauf les anciens liées à un client )
        $cloudis = Cloudi::where('is_client',0)->get();

        foreach($cloudis as $cloudi) {
            $recError = false;
            $url="";
            $pivotData = ['url' => Cloudder::secureShow($cloudi->path, eval($cloudi->config))];
            $this->cloudis()->add($cloudi, $pivotData);
            }
      }

}
