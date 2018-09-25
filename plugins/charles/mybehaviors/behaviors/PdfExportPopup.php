<?php namespace Charles\Mybehaviors\Behaviors;

use Backend\Classes\ControllerBehavior;
use October\Rain\Exception\ApplicationException;
use Renatio\DynamicPDF\Classes\PDFWrapper;
use Dom\Crm\Models\Settings;


use Config;
use Exception;
use Redirect;
use Response;
use Str;
use October\Rain\Support\Collection;
use yaml;
use Session;
use BackendAuth;

class PdfExportPopup extends ControllerBehavior
{

    /**
     * @inheritDoc
     */
    protected $requiredProperties = ['pdfConfig'];
    /**
     * @var array Configuration values that must exist when applying the primary config file.
     */
    protected $requiredConfig = ['modelClass', 'controllerName'];

    /**
     * @var Model Import model
     */
    public $model;

    protected $pdfExportFormWidget;





    public function __construct($controller)
    {
        parent::__construct($controller);
        /*
         * Build configuration
         */
        $this->config = $this->makeConfig($controller->pdfConfig, $this->requiredConfig);
        $this->pdfExportFormWidget = $this->createPdfExportFormWidget();
    }

    public function onLoadPdfExportForm()
    {
        /**
         * [$id du model]
         * @var string
         */
        $id = post('id');
        if(!$id) throw new ApplicationException('Please verifiy id model to export');

        $this->vars['modelId'] = post('id');


        return $this->makePartial('$/charles/mybehaviors/behaviors/pdfexport/_pdfexport_form.htm');

        

    }

    public function createPdfExportFormWidget() {
        $configExportpdf = new Collection($this->getConfig('form'));
        //opération pour retourver l'objet fields
        // !! attention l'objet field doit être en dernier !
        $configExportpdf = $configExportpdf->take(-1)->toArray();

        $config = $this->makeConfig($configExportpdf);
        $config->alias = 'myexportpdfformWidget';

        $config->arrayName = 'exportpdf_array';
        //$config->redirect = $this->getConfig('redirect').':id';

        $modelName = $this->getConfig('modelClass');
        $config->model = new $modelName;

        $widget = $this->makeWidget('Backend\Widgets\Form', $config);
        $widget->bindToController();

        return $widget;
    }



    public function onExportPdfValidation()
    {

        $dataForm = $this->pdfExportFormWidget->getSaveData();

        Session::put('dataForm', $dataForm);

        return true;
    }

     public function onLoadPdfExport()
    {
        /**
         * [$id du model]
         * @var string
         */
        $id = post('id');

        if(!$id) throw new ApplicationException('Please verifiy id model to export');

        
        return Redirect::to('/backend/dom/crm/'.$this->getConfig('controllerName').'/makepdf/'.$id);

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

        $data['creator'] = BackendAuth::getUser()->login;


        $dataForm = Session::pull('dataForm');
        if(!$dataForm) {
            $dataForm['english'] = false;
        }

        
        $settings = Settings::instance();

        $traduction = new Collection(Yaml::parse($settings->traduction));

        if($dataForm['english']) {
            $traduction = $traduction['en'];
        } else {
            $traduction = $traduction['fr'];
        }


         $data['txt'] = $traduction;
         $data['options'] = $dataForm;



        /**
         * Construction du nom du fichier. 
         */
        
         /**
         * @var string 
         */
        $prefix = $this->getConfig('prefix');

        /**
         * @var string 
         */
        $prefixCodeAttribut = $this->getConfig('prefix_code_attribut');


        $filename ='';

        if ($prefix) $filename .= $prefix;

        if ($prefixCodeAttribut) $filename .= $data[$prefixCodeAttribut];

        $filename .= '_'.$data->name;

        $filename = Str::slug($filename) . '.pdf';



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
