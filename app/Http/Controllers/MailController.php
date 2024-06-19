<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\NotificationEmail;
use Illuminate\Support\Facades\Mail;


class MailController extends Controller
{
    public static function sendMail($customerEmail,$endingDate,$bill,$days_left_to_end,$type="")
    {
        $data = [
                    'message' => 'Votre Souscription mensuelle au service de Bloosat prend fin dans'.$days_left_to_end.' jours',
                    'endingDate'=>$endingDate,
                    'bill'=>$bill,
                    'email_type'=>$type
                ];

        $x = Mail::to($customerEmail)->send(new NotificationEmail($data));


    }

}
