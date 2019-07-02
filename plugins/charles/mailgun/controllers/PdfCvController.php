<?php

namespace Charles\Mailgun\Controllers;

use Backend\Classes\ControllerBehavior;
use October\Rain\Exception\ApplicationException;
use Renatio\DynamicPDF\Classes\PDFWrapper;
use Charles\Marketing\Models\Settings;
use Charles\Marketing\Models\Experience;
use Charles\Mailgun\Models\Contact;
use Charles\Mailgun\Models\Visit;
use October\Rain\Support\Collection;


use Config;
use Exception;
use Redirect;
use Response;
use Str;
use BackendAuth;
use Yaml;


class PdfCvController {

    public function index($user_id)
    {
        $templateCode = "cv_1";

        $data = Contact::where('key', $user_id)->first();
        if ($data === null) {
            throw new ApplicationException('model not found.');
        }


        $data['experiences'] =  Experience::with('projects', 'competences')->get();
        $settings = Settings::instance()->value;
        
        /**
         * TRAVAIL SUR LES OPTIONS DU CV
         */
        trace_log("travail sur les options du CV");
        $compostings = new \October\Rain\Support\Collection();
        foreach ($data->cloudis as $cloudi) {
            $compostings->put($cloudi->name, $cloudi->pivot->url );
        }
        
        $data['compostings'] = $compostings;
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
        $data['base_url_ctoa'] = getenv('URL_VUE');
        $data['contact_environement'] = $data->contactEnvironement;
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

            $data->visits()->add(new Visit(['type' => 'pdf']));

            return $pdf
                ->loadTemplate($templateCode, compact('data'))
                ->setOptions($options)
                ->stream();

        } catch (Exception $e) {
            throw new ApplicationException($e->getMessage());
        }
    }

}