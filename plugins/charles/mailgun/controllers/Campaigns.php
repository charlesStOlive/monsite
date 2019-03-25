<?php namespace Charles\Mailgun\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Event;
use Flash;
use Redirect;
use System\Classes\SettingsManager;

use Charles\Mailgun\Models\Campaign;


/**
 * Campaigns Back-end Controller
 */
class Campaigns extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController',
        'Charles.Mailgun.Behaviors.SendEmails',
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';

    public $requiredPermissions = ['charles.mailgun.*'];

    protected $duplicateCampaignWidget;



    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Charles.Mailgun', 'mailgun', 'side-menu-campaigns');
        // SettingsManager::setContext('charles.mailgun', 'settings');


        $this->duplicateCampaignWidget = $this->duplicateCampaignFormWidget();

    }

    public function duplicateCampaignFormWidget() {
        $config = $this->makeConfig('$/charles/mailgun/models/campaign/fields_duplicate.yaml');
        $config->alias = 'myduplicateformWidget';

        $config->arrayName = 'duplicate_array';
        $config->redirect = ' charles/mailgun/campaigns/update/:id';
        $config->model = new \Charles\Mailgun\Models\Campaign;

        $widget = $this->makeWidget('Backend\Widgets\Form', $config);
        $widget->bindToController();

        return $widget;
    }

    public function onLoadDuplicateCampaign() {
        $this->vars['duplicateCampaignFormWidget'] = $this->duplicateCampaignWidget;
        $this->vars['campaignId'] = post('campaignId');

        return $this->makePartial('duplicate_form');
    }

    protected function onDuplicateValidation(){
        $data = $this->duplicateCampaignWidget->getSaveData();

        $sourceCampaign = Campaign::with('picture')->find(post('campaign_id'));
        $newCampaign = $sourceCampaign->replicate();

        $newCampaign->date_info_update = $data['date_info_update'];
        $newCampaign->name = $data['name'];
        $newCampaign->status_id = 1;
        $newCampaign->sent_at = null;
        $newCampaign->nb_email_sent = 0;
        $newCampaign->picture = $sourceCampaign->picture;
        $newCampaign->save();

        Flash::info("La campagne a été dupliquée ");
        return Redirect::refresh();

    }

}
