<?php namespace Charles\Mybehaviors\Behaviors;

use Backend\Classes\ControllerBehavior;
use October\Rain\Exception\ApplicationException;
use Renatio\DynamicPDF\Classes\PDFWrapper;
use Charles\Marketing\Models\Settings;
use Charles\Marketing\Models\Experience;
use October\Rain\Support\Collection;


use Config;
use Exception;
use Redirect;
use Response;
use Str;
use BackendAuth;
use Yaml;

class PdfCvExport extends ControllerBehavior
{

    /**
     * @inheritDoc
     */
    protected $requiredProperties = ['pdfConfig'];
    /**
     * @var array Configuration values that must exist when applying the primary config file.
     */
    protected $requiredConfig = ['modelClass', 'controllerRoute'];

    /**
     * @var Model Import model
     */
    public $model;





    public function __construct($controller)
    {
        parent::__construct($controller);

        /*
         * Build configuration
         */
        $this->config = $this->makeConfig($controller->pdfConfig, $this->requiredConfig);
    }

    public function onLoadPdfExport()
    {
        /**
         * [$id du model]
         * @var string
         */
        //cet evenement liée au controleur permet de demander un enregistrement du modèle. 
        $this->controller->fireEvent('dom.crm.quotes.request_save');
        $id = post('id');
        if(!$id) throw new ApplicationException('Please verifiy id model to export');

        
        return Redirect::to('/backend/'.$this->getConfig('controllerRoute').'/makepdf/'.$id)->with('message', 'Login Failed');;
;

    }




    public function makepdf($id)
    {
        /**
         * [$id du model]
         * @var string
         */
        //$id = post('id');
        //$lang = post('lang');

        if(!$id) throw new ApplicationException('Please verifiy id model to export');



        /**
         * [$model model de base pour l'export]
         * @var Collection
         */
        $model = $this->exportGetModel();


        /**
         * @var string permet de savoir s'il faut prendre en compte l'attribut ou le modele ( si attribut vide )  
         */
        $attribute = $this->getConfig('attribute');
        
        /**
         * @var bool 
         */
        $templateCode = $this->getConfig('template_code');



        

       


        $data = $model::find($id);
        if ($data === null) {
            throw new ApplicationException('model not found.');
        }
        $prefix;

        if($data->is_english) {
            $prefix = $this->getConfig('prefix_en');
        } else {
            $prefix = $this->getConfig('prefix_fr');
        }
        

        /**
         * Construction du nom du fichier. 
         */

        /**
         * @var string 
         */
        $prefixCodeAttribut = $this->getConfig('prefix_code_attribut');


        $filename ='';

        if ($prefix) $filename .= $prefix;

        if ($prefixCodeAttribut) $filename .= "_". $data[$prefixCodeAttribut];

        $filename .= '_'.Str::slug($data->client->name).'_'.Str::slug($data->fname);

        $filename = $filename . '.pdf';



        /**
         * Verification si relation du model  = nesdetmodel. 
         */
        /**
         * @var bool 
         */
        $nestedModel = $this->getConfig('nested_model');


        if($nestedModel) {
            $data[$nestedModel] = $data[$nestedModel]->toNested();
            //$data->quotesdetails = $data->quotesdetails->toNested();

        }


        /**
         * A modifier plus tard j'appelle en dur une liaison
         */
        $data['experiences'] =  Experience::with('projects', 'competences')->get();
        $settings = Settings::instance()->value;
        
        /**
         * TRAVAIL SUR LES OPTIONS DU CV
         */
        trace_log("travail sur les options du CV");
        if($data->client) {
            if($data->client->base_color) {
                $settings['base_color'] = $data->client->base_color;
            }
            if($data->client->is_cv_option) {
                $clientOption = $data->client->cv_option;
                if($clientOption['color']) $settings['cv_option']['color'] = $clientOption['color'];
                if($clientOption['title']) $settings['cv_option']['title'] = $clientOption['title'];
                if($clientOption['secteur']) $settings['cv_option']['secteur'] = $clientOption['secteur'];
                if(array_key_exists('technical', $clientOption)) {
                    $settings['cv_option']['technical'] = $clientOption['technical'];
                }
                if(array_key_exists('marketing', $clientOption)) $settings['cv_option']['marketing'] = $clientOption['marketing'];
                if(array_key_exists('soft_skills', $clientOption)) $settings['cv_option']['soft_skills'] = $clientOption['soft_skills'];
                if(array_key_exists('fonctionel', $clientOption)) $settings['cv_option']['fonctionel'] = $clientOption['fonctionel'];
            }
        }
        $data['settings'] = $settings;
        trace_log($data['settings']);
        trace_log($data->name);
        trace_log($data->client->name);
        trace_log($data->client->logo);

        /**
         * Construction du pdf
         */
        try {
            /** @var PDFWrapper $pdf */
            $pdf = app('dynamicpdf');

            $options = [
                'logOutputFile' => storage_path('temp/log.htm'),
                'isRemoteEnabled' => true,
            ];

            return $pdf
                ->loadTemplate($templateCode, compact('data'))
                ->setOptions($options)
                ->download($filename);

        } catch (Exception $e) {
            throw new ApplicationException($e->getMessage());
        }
    }





    public function exportGetModel()
    {
        if ($this->model !== null) {
            return $this->model;
        }

        $modelClass = $this->getConfig('modelClass');

        if (!$modelClass) {
            throw new ApplicationException('Please specify the modelClass property for exporting');
        }

        return $this->model = new $modelClass;
    }
}
