<?php namespace Charles\Mybehaviors\Behaviors;

use Backend\Classes\ControllerBehavior;

use Excel;
use October\Rain\Support\Collection;
use October\Rain\Exception\ApplicationException;
use Flash;
use Redirect;


class ExportExcel extends ControllerBehavior
{
    /**
     * @inheritDoc
     */
    protected $requiredProperties = ['excelConfig'];

    /**
     * @var array Configuration values that must exist when applying the primary config file.
     */
    protected $requiredConfig = ['modelClass'];

    /**
     * @var Model Import model
     */
    public $model;




	//protected $exportExcelWidget;

	public function __construct($controller)
    {
        parent::__construct($controller);

        /*
         * Build configuration
         */
        $this->config = $this->makeConfig($controller->excelConfig, $this->requiredConfig);
        

        


        //$this->exportExcelWidget = $this->createExportExcelFormWidget();
    }


     //ci dessous tous les calculs pour permettre l'import excel. 

    public function exportexcel($id=null)
    {
        $model = $this->exportGetModel();
        $title = $this->getConfig('exportdefault[title]');
        $attribute = $this->getConfig('attribute');

        //récupération de la configuration de l'export par default
        $exportdefault = new Collection($this->getConfig('exportdefault[items]'));

        //création du tableau pour excel
        $excelArray= [];

        //récupération des label de la config 
        $headers   = $exportdefault->pluck('label')->all();

        //enregistrement des label dans le tableau excel
        array_push($excelArray, $headers); 

        $lines;
        
        
        if($attribute) {
            //si il y a un attribut, nous allons chercher la relation mais nous avons besoin de l'id
            if(!$id)  throw new ApplicationException('il manque l id du modèle de base');
           //On récupère l'enregistrement du modèle de base
            $linesSrc = $model::find($id);
            //on ne garde que la relation contacts. 
            $lines = $linesSrc[$attribute];

        } else {
            //Sinon on  récupère chaque enregistrement du modèles
            $lines = $model::all();

        }
        
        


        //on parcours le modèle
        foreach($lines as $line) {
            $idModel = $line->id;
            //création d'un object vide
            $temp = [];
            foreach($exportdefault as $key => $value )
            {

                if (str_is('*@*', $key)) {
                    // s'il y a un arobas c'est donc une liaison. 
                    //On cherche la liaison  on prend tt les caractères avant @
                    $link = strstr($key, '@', true);
                    //On cherche l'attribut   on prend tt les caractères après @
                    $select = substr($key, strpos($key, "@") + 1);  
                    //On le met dans l'objet
                    $temp[] = $line[$link][$select];

                    
                } else {
                    // ce n'est pas une relation. 
                    if(is_array($line[$key]))
                    {
                        //si les velurs sont un tableaux on les transforme en texte
                        $temp[] = implode(',',$line[$key]);
                    } else {
                        //sinon on prend juste la valeur. 
                        $temp[] = $line[$key];
                    }
                }
                                
                
            }

            //l'objet est ajouté dans le tableau execel.
            array_push($excelArray, $temp); 

        }

        $xls = Excel::create($title, function($excel) use ($excelArray,$title ) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle($title);
            $excel->setCreator('WEB')->setCompany('DOM');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet($title, function($sheet) use ($excelArray) {
                $sheet->fromArray($excelArray, null, 'A1', false, false);
            });

        });
        return $xls->download('xlsx');



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