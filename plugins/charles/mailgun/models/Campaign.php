<?php namespace Charles\Mailgun\Models;

use Model;
use Charles\Folies\Models\Contact;

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
        'courtiers' => [
            'Charles\Folies\Models\Contact',
            'table' => 'charles_mailgun_campaign_courtier',
            'pivot' => ['result_type', 'email', 'mg_timestamp'],
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

        $courtier = Contact::where('code_asp', '=', $code_asp)->first();
        $existingEntry = $courtier->campaigns()->where('id', $campaignId)->exists();

        trace_log($existingEntry);
        dd($existingEntry);

        return $existingEntry->count();
    }
    public function getContactsOptinAttribute()
    {
        return Contact::with(['gammes', 'interlocuteur'])->where('optin', true)->get();
    }

    public function getContactsEligiblesAttribute()
    {
        $courtier = [];
        $allContacts = Contact::get();
        $courtiers['eligibles'] = $allContacts;
        $courtiers['totalNotEligibles'] = 0;
        $courtiers['total'] = $allContacts->count();

        foreach ($allContacts as $key => $courtier) {
            if(!$courtier->Eligible) {
                $courtiers['eligibles']->forget($key);
                $courtiers['totalNotEligibles']++;
            }
        }
        $courtiers['totalEligibles'] = $courtiers['total'] -  $courtiers['totalNotEligibles'];
        trace_log($courtiers['totalEligibles']);


        return $courtiers;

    }

    public function getSentCampaignOptions() {
        return $this->where('status_id', '2')->lists('name', 'id');
    }

    public function getTotalWarningAttribute() { 
        
       
        return $this->courtiers()
            ->where(function($q){
                $q->where('result_type', 'waiting')
                    ->orWhere('result_type', 'failed');
            })->count();
    }

    public function getTotalDeliveredAttribute() {
        
        return $this->courtiers()->wherePivot('result_type','=' ,'delivered')->count();
    }

    public function getTotalOpenedAttribute() {
        
        return $this->courtiers()
            ->where(function($q){
                $q->where('result_type', 'opened')
                    ->orWhere('result_type', 'clicked');
            })->count();
    }

    public function getTotalSpamAttribute() {
        
        return $this->courtiers()
            ->where(function($q){
                $q->where('result_type', 'unsubscribed')
                    ->orWhere('result_type', 'complained');
            })->count();
    }
}
