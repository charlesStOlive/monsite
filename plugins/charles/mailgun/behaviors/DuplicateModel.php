<?php namespace Charles\Mailgun\Behaviors;

use Backend\Classes\ControllerBehavior;

use Charles\Crm\Models\Contact;
use Charles\Crm\Models\Interlocuteur;


use October\Rain\Support\Collection;
use October\Rain\Exception\ApplicationException;
use Flash;
use Redirect;


class DuplicateModel extends ControllerBehavior
{
    /**
     * @inheritDoc
     */
    protected $requiredProperties = ['duplicateConfig'];

    /**
     * @var array Configuration values that must exist when applying the primary config file.
     */
    protected $requiredConfig = ['modelClass'];

    /**
     * @var Model Import model
     */
    public $model;

    protected $duplicateWidget;




	//protected $exportExcelWidget;

	public function __construct($controller)
    {
        parent::__construct($controller);

        /*
         * Build configuration
         */
        $this->config = $this->makeConfig($controller->duplicateConfig, $this->requiredConfig);
        $this->duplicateWidget = $this->createDuplicateFormWidget();
        

        


        //$this->exportExcelWidget = $this->createExportExcelFormWidget();
    }


     //ci dessous tous les calculs pour permettre l'import excel. 

    public function onLoadDuplicateForm()
    {
        $this->model = $this->exportGetModel();
        $title = $this->getConfig('title');

        $this->vars['modelId'] = post('id');

        return $this->makePartial('$/charles/mailgun/behaviors/duplicatemodel/_duplicate_form.htm');

    }

    public function createDuplicateFormWidget() {
        $configDuplication = new Collection($this->getConfig('duplication'));
        //opération pour retourver l'objet fields
        $configDuplication = $configDuplication->take(-1)->toArray();

        $config = $this->makeConfig($configDuplication);
        $config->alias = 'myduplicateformWidget';

        $config->arrayName = 'duplicate_array';
        $config->redirect = $this->getConfig('redirect').':id';

        $modelName = $this->getConfig('modelClass');
        $config->model = new $modelName;

        $widget = $this->makeWidget('Backend\Widgets\Form', $config);
        $widget->bindToController();

        return $widget;
    }

    public function onDuplicateValidation(){
        $data = $this->duplicateWidget->getSaveData();

        $modelName = $this->getConfig('modelClass');
        $sourceModel = $modelName::find(post('id'));
        $cloneModel = $sourceModel->replicate();

        $transformation = new Collection($this->getConfig('duplication[transformation]'));
        $manipulation = new Collection($this->getConfig('duplication[fields]'));

        $keys = $transformation->keys();
        foreach($transformation as $key => $value ) {
            //if(!$value) $value = null;
            $cloneModel[$key] = $value;
        }

        foreach($manipulation as $value ) {
            //if(!$value) $value = null;
            $cloneModel[$value] = $data[$value];
        }
        
        $cloneModel->save();

        Flash::info("Duplication effectuée");
        return Redirect::refresh();

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

    //  public function onLoadDuplicateForm()
    // {
    //     $model = $this->exportGetModel();
    //     $title = $this->getConfig('title');

    //     $transformation = new Collection($this->getConfig('duplication[transformation]'));
    //     $manipulation = new Collection($this->getConfig('duplication[manipulation]'));

    //     trace_log($transformation);
    //     trace_log($manipulation);

    //     $this->vars['modelId'] = post('id');

    //     return $this->makePartial('$/charles/mailgun/behaviors/duplicatemodel/_duplicate_form.htm');


        // //création du tableau pour excel
        // $excelArray= [];

        // //récupération des label de la config 
        // $headers   = $exportdefault->pluck('label')->all();

        // //enregistrement des label dans le tableau excel
        // array_push($excelArray, $headers); 

        // $lines;
        
        
        // if($attribute) {
        //     //si il y a un attribut, nous allons chercher la relation mais nous avons besoin de l'id
        //     if(!$id)  throw new ApplicationException('il manque l id du modèle de base');
        //    //On récupère l'enregistrement du modèle de base
        //     $linesSrc = $model::find($id);
        //     //on ne garde que la relation contacts. 
        //     $lines = $linesSrc[$attribute];

        // } else {
        //     //Sinon on  récupère chaque enregistrement du modèles
        //     $lines = $model::all();

        // }
        
        


        // //on parcours le modèle
        // foreach($lines as $line) {
        //     $idModel = $line->id;
        //     //création d'un object vide
        //     $temp = [];
        //     foreach($exportdefault as $key => $value )
        //     {

        //         if (str_is('*@*', $key)) {
        //             // s'il y a un arobas c'est donc une liaison. 
        //             //On cherche la liaison  on prend tt les caractères avant @
        //             $link = strstr($key, '@', true);
        //             //On cherche l'attribut   on prend tt les caractères après @
        //             $select = substr($key, strpos($key, "@") + 1);  
        //             //On le met dans l'objet
        //             $temp[] = $line[$link][$select];

                    
        //         } else {
        //             // ce n'est pas une relation. 
        //             if(is_array($line[$key]))
        //             {
        //                 //si les velurs sont un tableaux on les transforme en texte
        //                 $temp[] = implode(',',$line[$key]);
        //             } else {
        //                 //sinon on prend juste la valeur. 
        //                 $temp[] = $line[$key];
        //             }
        //         }
                                
                
        //     }

        //     //l'objet est ajouté dans le tableau execel.
        //     array_push($excelArray, $temp); 

        // }

        // $xls = Excel::create($title, function($excel) use ($excelArray,$title ) {

        //     // Set the spreadsheet title, creator, and description
        //     $excel->setTitle($title);
        //     $excel->setCreator('WEB')->setCompany('DOM');

        //     // Build the spreadsheet, passing in the payments array
        //     $excel->sheet($title, function($sheet) use ($excelArray) {
        //         $sheet->fromArray($excelArray, null, 'A1', false, false);
        //     });

        // });
        // return $xls->download('xlsx');
        // 
        // 



    // }

    
}