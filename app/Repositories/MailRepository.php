<?php

namespace App\Repositories;

require_once('/var/www/html/vendor/autoload.php');

use App\Models\HouseContact;
use App\Models\HouseContactBanned;
use Exception;

class MailRepository
{
    public $mailchimp = null;

    public function __construct()
    {
        $this->mailchimp = new \MailchimpTransactional\ApiClient();
        $this->mailchimp->setApiKey(env('MANDRILL_TOKEN'));
    }
    public function sendHouseContact($params)
    {
        // check if spam
        $contactBanned = HouseContactBanned::whereEmail($params['email'])->first();
        if (!$contactBanned) {
            // check total sent email in the day and in the last 5 min
            try {
                // send email
                $mail["mail_subject"] =
                    $template_content = array(
                        array(
                            'name' => 'message',
                            'content' => $params['message']
                        ),
                        array(
                            'name' => 'house_id',
                            'content' => $params['house_id']
                        ),
                        array(
                            'name' => 'house_title',
                            'content' => $params['house_title']
                        ),
                        array(
                            'name' => 'search_ref_url',
                            'content' => $params['house_url']
                        ),
                        array(
                            'name' => 'sender_firstname',
                            'content' => $params['firstname']
                        ),
                        array(
                            'name' => 'sender_lastname',
                            'content' => $params['lastname']
                        ),
                        array(
                            'name' => 'sender_phone',
                            'content' => $params['phone']
                        ),
                        array(
                            'name' => 'sender_email',
                            'content' => $params['email']
                        ),
                        array(
                            'name' => 'sender_startdate',
                            'content' => $params['date_from'] ?? 'NC'
                        ),
                        array(
                            'name' => 'sender_stay',
                            'content' => $params['stay'] ?? 'NC'
                        ),
                        array(
                            'name' => 'sender_persons',
                            'content' => $params['number_people'] ?? 'NC'
                        )
                    );
                $to = [];
                array_push($to, [
                    "email" => $params['house_email'],
                    "type" => "to"
                ]);
                $message = [
                    "from_email" => env('MAIL_FROM'),
                    'from_name'  => 'Vacancesweb',
                    "subject" => 'Contact pour votre location de vacances de la part du site Vacancesweb',
                    "to" => $to,
                    "headers" => ["Reply-To" => $params['house_email']],
                    'global_merge_vars' => $template_content
                ];
                HouseContact::insert([
                    'user_id' => $params['user_id']??null,
                    'house_id' => $params['house_id']??null,
                    'firstname' => $params['firstname']??null,
                    'lastname' => $params['lastname']??null,
                    'phone' => $params['phone']??null,
                    'email' => $params['email']??null,
                    'lang' => $params['lang']??null,
                    'message' => $params['message']??null,
                    'created_at' => now(),
                ]);
                $this->mailchimp->messages->sendTemplate([
                    "template_name" => "ad request info " . ($params['lang'] ?? 'fr'),
                    "template_content" => $template_content,
                    "message" => $message,
                ]);
            } catch (Exception $e) {
                logger($e->getMessage());
            }
        }
    }
}
