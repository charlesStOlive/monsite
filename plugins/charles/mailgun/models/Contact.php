<?php namespace Charles\Mailgun\Models;

use Model;
use Validator;

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

    protected $jsonable = ['message_perso'];

    protected $casts = [
        'nameFname' => 'string',
    ];

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
        'moas' => [
            'Charles\Marketing\Models\Moa',
            'table' => 'charles_mailgun_contact_moa',
        ],
        'segments' => [
            'Charles\Mailgun\Models\Segment',
            'table' => 'charles_mailgun_contact_segment',
        ],
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function getEligibleAttribute() {
        // trace_log("éligibilité");
        // $campaigns = $this->campaigns;

        // if($this->error) {
        //     return false;
        // }

        // if(!$this->optin) {
        //     return false;
        // }

        //  if(strlen($this->email)<3) {
        //     trace_log("this-email");
        //     return false;
        // }

        //validation de l'email
        // $rules = ["email" => 'required|email'];
        // $validator = Validator::make(["email" => $this->email], $rules );
        // if ($validator->fails()) {
        //     trace_log("validator->fails");
        //     //
        //     return false;
        // }
            
        

        // if(isset($campaigns)){
        //     $badResults = ['complained', 'unsubscribed', 'failed'];
        //     foreach($campaigns as $campaign) {
        //         if(($this->email == $campaign->pivot->email) && in_array($campaign->pivot->result_type, $badResults)) {
        //             return false;
        //         }
        //     }
        //     return true;

        // }
        return true;


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

}
