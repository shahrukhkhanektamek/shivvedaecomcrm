<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MailModel;

class MailController extends Controller
{
    public function sendEmail()
    {

        $details = [
            'to'=>'sharukhkhanektamek1998@gmail.com',
            'view'=>'mailtemplate.registration',
            'subject'=>'Registration Mail',
            'title' => 'Mail from Laravel 11',
            'body' => 'This is a test email using Laravel 11.'
        ];
        MailModel::registration($details);

        
        return "Email Sent!";
    }
}