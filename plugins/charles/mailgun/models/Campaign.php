<?php namespace Charles\Mailgun\Models;

use Model;
use Charles\Mailgun\Models\Contact;

/**
 * Campaign Model
 */
class Campaign extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'charles_mailgun_campaigns';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['id'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    protected $jsonable = ['messages'];

    /**
     * @var array Relations
     */
    public $hasOne = [
    ];
    public $hasMany = [];
    public $belongsTo = [
        'status' => ['Charles\Mailgun\Models\Status',]
    ];
    public $belongsToMany = [
        'contacts' => [
            'Charles\Mailgun\Models\Contact',
            'table' => 'charles_mailgun_campaign_contact',
            'pivot' => ['result_type','perso', 'email', 'mg_timestamp'],
            'pivotModel' => 'Charles\Mailgun\Models\CampaignContactPivot'
            ],
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [
        'picture' => ['System\Models\File']
    ];
    public $attachMany = [];

    public function getVerifAttribute() {
        $campaignId = 1;
        $newTypeValue = 'delivered';
        $code_asp = 68445;

        $contact = Contact::where('code_asp', '=', $code_asp)->first();
        $existingEntry = $contact->campaigns()->where('id', $campaignId)->exists();

        trace_log($existingEntry);
        dd($existingEntry);

        return $existingEntry->count();
    }
    public function getContactsOptinAttribute()
    {
        return Contact::where('optin', true)->get();
    }

    public function getContactsEligiblesAttribute()
    {
        $contact = [];
        $allContacts = Contact::get();
        $contacts['eligibles'] = $allContacts;
        $contacts['totalNotEligibles'] = 0;
        $contacts['total'] = $allContacts->count();

        foreach ($allContacts as $key => $contact) {
            if(!$contact->Eligible) {
                $contacts['eligibles']->forget($key);
                $contacts['totalNotEligibles']++;
            }
        }
        $contacts['totalEligibles'] = $contacts['total'] -  $contacts['totalNotEligibles'];


        return $contacts;

    }
    public function getActiveCampaignOptions() {
        //return $this->where('status_id', '2')->lists('name', 'id');
        return $this->lists('name', 'id');
    }

    public function getSentCampaignOptions() {
        //return $this->where('status_id', '2')->lists('name', 'id');
        return $this->lists('name', 'id');
    }

    public function getTotalWarningAttribute() { 
        
       
        return $this->contacts()
            ->where(function($q){
                $q->where('result_type', 'waiting')
                    ->orWhere('result_type', 'failed');
            })->count();
    }

    public function getTotalDeliveredAttribute() {
        
        return $this->contacts()->wherePivot('result_type','=' ,'delivered')->count();
    }

    public function getTotalOpenedAttribute() {
        
        return $this->contacts()
            ->where(function($q){
                $q->where('result_type', 'opened')
                    ->orWhere('result_type', 'clicked');
            })->count();
    }

    public function getTotalSpamAttribute() {
        
        return $this->contacts()
            ->where(function($q){
                $q->where('result_type', 'unsubscribed')
                    ->orWhere('result_type', 'complained');
            })->count();
    }
    //
    //
    /**
     * LISTS
     */
    //
    public function listCloudinaris($fieldName, $value, $formData)
    {
        return Cloudi::lists('name', 'name');
    }

    public function listContacts($fieldName, $value, $formData)
    {
        $contacts =  Contact::HasClientFilter(2)->get();
        $list = new \October\Rain\Support\Collection();
        foreach($contacts as $contact) {
            $list->put($contact->id,  $contact->name.' '.$contact->fname.' || '.$contact->client->name);

        }
        return $list;
    }
}
