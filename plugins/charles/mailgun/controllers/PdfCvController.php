<?php

namespace Charles\Mailgun\Controllers;

use Backend\Classes\ControllerBehavior;
use October\Rain\Exception\ApplicationException;
use Renatio\DynamicPDF\Classes\PDFWrapper;
use Charles\Marketing\Models\Settings;
use Charles\Mailgun\Models\Settings as MailgunSettings;
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

    public function index($user_key)
    {
        $templateCode = "cv_1";
        $data = $this->preparePdf($user_key);
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
                ->save(storage_path('app/media/cv/'.$data->cv_name.'.pdf'))
                ->stream();

        } catch (Exception $e) {
            throw new ApplicationException($e->getMessage());
        }
    }

    public function lettreMotivation($user_key)
    {
        $templateCode = "lettre-de-motivation";
        $data = $this->prepareLM($user_key);
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

            //$data->visits()->add(new Visit(['type' => 'lm_pdf']));


            return $pdf
                ->loadTemplate($templateCode, compact('data'))
                ->setOptions($options)
                ->download($data['file_name']);

        } catch (Exception $e) {
            throw new ApplicationException($e->getMessage());
        }
    }

    public function test($user_key)
    {
        $templateCode = "cv_1";
        $data = $this->preparePdf($user_key);
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

            trace_log(storage_path('app/media/cv/'.$data->cv_name.'.pdf'));

            return $pdf
                ->loadTemplate($templateCode, compact('data'))
                ->setOptions($options)
                ->save(storage_path('app/media/cv/'.$data->cv_name.'.pdf'))
                ->stream();

        } catch (Exception $e) {
            throw new ApplicationException($e->getMessage());
        }
    }

    public function downloadCv($user_key)
    {
        $templateCode = "cv_1";
        $data = $this->preparePdf($user_key);
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
                ->download($data['file_name']);

        } catch (Exception $e) {
            throw new ApplicationException($e->getMessage());
        }
    }

    public function preparePdf($user_key) {
        //
        $data = Contact::where('key', $user_key)->first();
        if ($data === null) {
            throw new ApplicationException('model not found.');
        }
        //
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
        $data['base_color'] = $data->clientColor;
        $data['settings'] = $settings;
        $data['base_url_ctoa'] = getenv('URL_VUE');
        $data['contact_environement'] = $data->contactEnvironement;
        $data['file_name'] = "cv_saint_olive_pour_".$data->client->slug.".pdf";

        return $data;

    }

    public function prepareLM($user_key) {
        //
        $data = Contact::where('key', $user_key)->first();
        if ($data === null) {
            throw new ApplicationException('model not found.');
        }
        //
        $myMessages = [];
        foreach(MailgunSettings::get('lettre_motivation') as $msg) {
            $myMessages[$msg['code']] = $msg['value'];
            $msgPerso = $this->getMessagePerso($data->messages_lm, $msg['code'] );
            if($msgPerso)  $msg['value'] = $msgPerso;
            $myMessages[$msg['code']] = $msg['value'];
        }
        trace_log($myMessages);
        $data['file_name'] = "lettre_m_saint_olive_pour_".$data->client->slug.".pdf";
        $data['base_color'] = $data->clientColor;
        $data['contents'] =  $myMessages;
        $data['base_url_ctoa'] = getenv('URL_VUE');
        $data['contact_environement'] = $data->contactEnvironement;
        $data['compostings'] = $data->cloudisDefault;
        trace_log($data);
        return $data;

    }

    public function getMessagePerso($msgs, $value) {
        $messageToReturn = null;
        if(!$msgs) return null;
        foreach($msgs as $msg) {
            if ($msg['code'] ==  $value) {
                $messageToReturn = $msg['value'];
            }
        }
        return $messageToReturn;
    }

}