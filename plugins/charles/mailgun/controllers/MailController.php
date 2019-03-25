<?php

namespace Charles\Mailgun\Controllers;

use Config;
use Illuminate\Http\Request;

use Charles\Mailgun\Models\Contact;
use Charles\Mailgun\Models\Log;

class MailController {

    public function mails(Request $request)
    {
        $mailDatas = $request->all();

        if($this->verify(
            Config::get('services.mailgun.secret'), //apikey
            $mailDatas['signature']['token'], //token
            $mailDatas['signature']['timestamp'], //timestamp
            $mailDatas['signature']['signature'] ) // signature
        ) {

            $code_asp = $mailDatas['event-data']['user-variables']['code_asp'];
            $contact = Contact::where('code_asp', '=', $code_asp)->first();
            $campaignId = $mailDatas['event-data']['user-variables']['campaign_id'];
            $emailRecipient = $mailDatas['event-data']['recipient'];
            $mgTimestamp = $mailDatas['signature']['timestamp'];

            $existingEntry = $contact->campaigns()->where('id', $campaignId)->exists();

            if($existingEntry) {
                $this->updateOnPriority($contact, $campaignId, $mailDatas['event-data']['event'], $mgTimestamp, $emailRecipient);
            } else {
                $contact->campaigns()->attach($campaignId, ['result_type' => $mailDatas['event-data']['event'], 'mg_timestamp' => $mgTimestamp, 'email' => $emailRecipient]);
            }
            return 200;
        }
        return 406;
    }


    // Permet de vérifier l'intégrité du mail
    private function verify($apiKey, $token, $timestamp, $signature)
    {
        //check if the timestamp is fresh
        if (abs(time() - $timestamp) > 15) {
            return false;
        }

        //returns true if signature is valid
        return hash_hmac('sha256', $timestamp.$token, $apiKey) === $signature;
    }

    // Met à jour la liaison uniquement si la priortié est supérieure
    private function updateOnPriority($contact, $campaignId, $newTypeValue, $mgTimestamp, $emailRecipient) // liste des paramètres à préciser
    {
        $events = [
            'waiting' => 0,
            'accepted' => 1,
            'rejected' => 1,
            'delivered' => 2,
            'failed' => 2,
            'opened' => 3,
            'clicked' => 4,
            'unsubscribed' => 5,
            'complained' => 5
        ];

        $oldTypeValue = $contact->campaigns->where('id', $campaignId)->first()->pivot->result_type;

        if ($events[$newTypeValue] >  $events[$oldTypeValue]) {
            $contact->campaigns()->updateExistingPivot($campaignId, ['result_type' => $newTypeValue, 'mg_timestamp' => $mgTimestamp, 'email' => $emailRecipient ]);

            // on vérifie s'il s'agit d'un unsubscribe et on met à jour le courtier
            if ($events[$newTypeValue] == 4) {
                $contact->optin = 0;
                $contact->save();
            }
        } else {
        }
    }


}