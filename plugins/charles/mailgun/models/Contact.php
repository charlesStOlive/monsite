<?php namespace Charles\Mailgun\Models;

use Charles\Marketing\Models\Client;
use Charles\Marketing\Models\Project;
use Charles\Marketing\Models\Moa;
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

    protected $jsonable = ['message_perso', 'messages_lm'];

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

    public function getClientColorAttribute() {
        $value=null;
        $value = Settings::get('base_color');
        if($this->client) {
            if($this->client->base_color) $value = $this->client->base_color;
        }
        return $value;
    }
    public function getProjectsDefaultAttribute() {
        $value= null;
        if($this->projects()->count()) {
            $value = $this->projects()->get(['name', 'slug', 'accroche'])->toArray();
        } else {
            $value = Project::whereIn('id', array(2, 6, 8))->get(['name', 'slug', 'accroche'])->toArray();
        }
        return $value;
    }
    public function getMoasDefaultAttribute() {
        $value= null;
        if($this->moas()->count()) {
            $value = $this->moas()->get(['name', 'slug', 'accroche'])->toArray();
        } else {
            $value = Moa::whereIn('id', array(4, 7, 8))->get(['name', 'slug', 'accroche'])->toArray();
        }
        return $value;
    }
    public function getCloudisDefaultAttribute() {
        $values = new \October\Rain\Support\Collection();
        foreach ($this->cloudis as $cloudi) {
            $values->put($cloudi->slug, $cloudi->pivot->url );
        }
        return $values;
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
    /**
     * Lists
     */
    
    public function listParagraphCode($fieldName, $value, $formData)
    {
        $myArray = new \October\Rain\Support\Collection();
        $campaigns = Campaign::where('use_personalisation',1)->get();
        
        foreach($campaigns as $campaign) {
            $code = new \October\Rain\Support\Collection($campaign->messages);
            $tempArray = $code->pluck('code', 'code');
            $myArray = $myArray->merge($tempArray);
            trace_log($myArray);
        }
        return $myArray;
    }

    public function listParagraphLMCode($fieldName, $value, $formData)
    {
        $myArray = '';
        $lm = new \October\Rain\Support\Collection(Settings::get('lettre_motivation'));
        $myArray =  $lm->pluck('code', 'code');
        return $myArray;
    }
    public function listCloudinaris()
    {
        return Cloudi::lists('name', 'slug');
    }
}
