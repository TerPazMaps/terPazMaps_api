<?php

namespace App\Services;

use App\Mail\PasswordUpdate;
use App\Interfaces\ServiceInterface;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    protected $mail;

    public function __construct()
    {
        $this->mail = Mail::class;
    }
    public function sendEmailPasswordUpadate($email, $user, $token)
    {
        return $this->mail::to($email)->send(new PasswordUpdate($user, $token));
    }

}
