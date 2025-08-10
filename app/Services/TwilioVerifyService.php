<?php

namespace App\Services;

use Twilio\Rest\Client;
use Twilio\Exceptions\RestException;

class TwilioVerifyService
{
    protected Client $client;
    protected string $serviceSid;

    public function __construct()
    {
        $this->client = new Client(config('services.twilio.sid'), config('services.twilio.token'));
        $this->serviceSid = config('services.twilio.verify_service_sid');
    }

    /** Start a verification (send OTP). $channel: 'sms'|'call'|'whatsapp'|'email' etc. */
    public function startVerification(string $to, string $channel = 'sms')
    {
        try {
            return $this->client->verify->v2
                ->services($this->serviceSid)
                ->verifications
                ->create($to, $channel);
        } catch (RestException $e) {
            throw $e; // handle/log in controller
        }
    }

    /** Check verification code */
    public function checkVerification(string $to, string $code)
    {
        try {
            return $this->client->verify->v2
                ->services($this->serviceSid)
                ->verificationChecks
                ->create([
                    'to' => $to,
                    'code' => $code,
                ]);
        } catch (RestException $e) {
            throw $e;
        }
    }

    public function client()
    {
        return $this->client;
    }
}
