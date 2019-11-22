<?php namespace Charles\Marketing\Models;

use Model;
use Charles\Mailgun\Models\Segment;
use Charles\Mailgun\Models\Cloudi;
use Charles\Mailgun\Models\Settings as SettingsMailgun;
use \ToughDeveloper\ImageResizer\Classes\Image;

/**
 * Secteur Model
 */
class Secteur extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'charles_marketing_secteurs';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = ['cv_option', 'messages_lm'];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        'clients' => ['Charles\Marketing\Models\Client'],
    ];
    public $belongsTo = [
        'cloudi' => ['Charles\Mailgun\Models\Cloudi'],
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    /**
     * Listes
     */

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
    public function listSegments($fieldName, $value, $formData)
    {
        return Segment::lists('name', 'id');
    }

    public function listParagraphLMCode($fieldName, $value, $formData)
    {
        $myArray = '';
        $lm = new \October\Rain\Support\Collection(SettingsMailgun::get('lettre_motivation'));
        trace_log("lm".$lm);
        $myArray =  $lm->pluck('code', 'code');
        return $myArray;
    }

    /**
     * GETTER
     */
    public function getSiteIntroFinalAttribute() {
        if(!$this->site_intro) {
            trace_log("Site intro est vide");
            return Settings::get('site_intro');
        } else {
            return $this->site_intro;
        }
    }

    public function listCloudinaris($fieldName, $value, $formData)
    {
        return Cloudi::lists('name', 'slug');
    }
}
