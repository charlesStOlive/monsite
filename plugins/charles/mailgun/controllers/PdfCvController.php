<?php

namespace Charles\Mailgun\Controllers;

use Backend\Classes\ControllerBehavior;
use October\Rain\Exception\ApplicationException;
use Renatio\DynamicPDF\Classes\PDFWrapper;
use Charles\Marketing\Models\Settings;
use Charles\Mailgun\Models\Settings as MailgunSettings;
use Charles\Marketing\Models\Experience;
use Charles\Marketing\Models\Secteur;
use Charles\Mailgun\Models\Contact;
use Charles\Mailgun\Models\Visit;
use October\Rain\Support\Collection;
use Twig;


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

    public function download_lm($user_key)
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

    public function stream_lm($user_key)
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
                ->stream();

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
        trace_log($data['settings']);
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
            $settings['cv_option'] = $data->client->cvMessages;
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
        $contact = Contact::where('key', $user_key)->first();
        if ($contact === null) {
            throw new ApplicationException('model not found.');
        }
        //
        $myMessages = new \October\Rain\Support\Collection();;
        $baseMessages = MailgunSettings::get('lettre_motivation');
        $messagesSecteur = $contact->client->secteur->messages_lm;
        foreach($baseMessages as $msg) {
            $msgPerso = $this->getMessagePerso($contact->messages_lm, $msg['code'] );
            $msgSecteur = $this->getMessagePerso($messagesSecteur, $msg['code'] );
            if($msgSecteur)  $msg['value'] = $msgSecteur;
            if($msgPerso)  $msg['value'] = $msgPerso;
            $msg['value'] = Twig::parse($msg['value'], compact('contact'));
            $myMessages->put($msg['code'], $msg);
        }
        trace_log($myMessages);
        $contact['file_name'] = "lettre_m_saint_olive_pour_".$contact->client->slug.".pdf";
        $contact['base_color'] = $contact->clientColor;
        $contact['contents'] =  $myMessages;
        $contact['base_url_ctoa'] = getenv('URL_VUE');
        $contact['contact_environement'] = $contact->contactEnvironement;
        $contact['compostings'] = $contact->cloudisDefault;
        trace_log($contact);
        return $contact;

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