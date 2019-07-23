<?php namespace Charles\Troisd\Models;

use Model;

/**
 * Hp Model
 */
class Hp extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'charles_troisd_hps';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    protected $jsonable = ['position', 'content', 'launch_mesh_animes'];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [
        'mesh' => ['Charles\troisd\Models\Mesh'],
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function listMeshAnimes($fieldName, $value, $formData)
    {
        trace_log($this->mesh->scene->id);
            
        $meshs = Mesh::where('scene_id', $this->mesh->scene->id )->get(['slug','mesh_animes']);
        //trace_log($meshs->get(['mesh_animes']));
        $anims = new \October\Rain\Support\Collection();
        foreach($meshs as $mesh) {
            foreach($mesh->mesh_animes as $anime) {
                $anims->put($mesh->slu.'**'.$anime['slug'], $mesh->slug.' -> '.$anime['slug']);                
            }
        }
        trace_log($anims);
        return $anims;
    }
    public function listMeshs($fieldName, $value, $formData)
    {
        return Mesh::where('scene_id', $this->mesh->scene->id )->lists('name', 'id');
    }
}
