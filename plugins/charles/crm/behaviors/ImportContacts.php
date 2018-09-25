<?php namespace Charles\Crm\Behaviors;

use Backend\Classes\ControllerBehavior;

use Charles\Crm\Models\Contact;
use Charles\Crm\Models\Compagny;


use Excel;
use October\Rain\Support\Collection;
use October\Rain\Exception\ApplicationException;
use Flash;
use Redirect;


class ImportContacts extends ControllerBehavior
{


	protected $importContactsWidget;

	public function __construct($controller)
    {
        parent::__construct($controller);

        $this->importContactsWidget = $this->createImportContactsFormWidget();
    }


     //ci dessous tous les calculs pour permettre l'import excel. 

    public function onLoadImportContactsForm()
    {
       $this->vars['importContactsWidget'] = $this->importContactsWidget;

        return $this->makePartial('$/dom/folies/behaviors/importcontacts/_import_form.htm');
    }

    protected function createImportContactsFormWidget()
    {
        $config = $this->makeConfig('$/dom/folies/models/uploadexcel/fields_contacts.yaml');

        $config->alias = 'uploadContactForm';

        $config->arrayName = 'UploadContact';

        $config->model = new \Charles\Crm\Models\UploadExcel;

        $widget = $this->makeWidget('Backend\Widgets\Form', $config);

        $widget->bindToController();

        return $widget;
    }

    public function onFileContactsValidation()
    {
        $data = $this->importContactsWidget->getSaveData();
        $sessionKey = \Input::get('_session_key');
        //$override_cost = $data['override_cost'];


        //import du fichier dans uploadtemp
        $uploadExcel = new \Charles\Crm\Models\UploadExcel;
        $uploadExcel->fill($data);
        $uploadExcel->save();

        $file = $uploadExcel
            ->upload()
            ->withDeferred($sessionKey) // how to get this session key dynamically?
            ->first();

        //le fichier est maintenant prêt à être traité. 
        Excel::load($file->getLocalPath(), function($reader) {
            // Getting all results
            $contacts = $reader->get();



            //le fichier est tout bon.

            //Gestion des compagnys : 
            $compagnys = $contacts->keyby('compagny_name');

            $nb_compagny_new = 0;
            $nb_compagny_update= 0;

            foreach($compagnys as $compagnyExcel) {
                $compagny = Compagny::where('name' , $compagnyExcel->compagny_name);

                $compagnyValue = [
                    'email' => $compagnyExcel->compagny_email,
                    'name' => $compagnyExcel->compagny_name,
                ];

                if($compagny->count()>0){
                    //sinon il existe déjà on l'update.
                    $nb_compagny_update++;
                    $compagny->update($compagnyValue); 
                } else {
                     //si le contact est un nouveau contact
                    $nb_compagny_new++;
                    $newCompagny = Compagny::create($compagnyValue);    
                }


            }

            

            //gestion des contacts

            $nb_contact_new = 0;
            $nb_contact_update= 0;

            
            foreach($contacts as $contactExcel) {
                //on cherche le contact sur le code ASP
                $contact = Contact::where('code_asp' , $contactExcel->code_asp);
                $compagnyId = Compagny::where('email' , $contactExcel->mail_compagny)->first()->id;
                //preparation des valeurs
                $contactValue = [
                    'code_asp' => $contactExcel->code_asp ,
                    'compagny_id' => $compagnyId ,
                    'cabinet' => $contactExcel->cabinet ,
                    'civ' => $contactExcel->civ ,
                    'sname' => $contactExcel->nom ,
                    'fname' => $contactExcel->prenom ,
                    'email' => $contactExcel->email ,
                ];

                if($contact->count()>0){
                    //sinon il existe déjà on l'update.
                     $nb_contact_update++;
                    $contact->update($contactValue); 
                } else {
                     //si le contact est un nouveau contact
                    $nb_contact_new++;
                    $newContact = Contact::create($contactValue);    
                }
            }
            


            Flash::info("Nombre de contacts importés $nb_contact_new , nombre de contact updaté  $nb_contact_update");
            
        });

       return Redirect::refresh();
          
    }
}