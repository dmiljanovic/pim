<?php

namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;

/**
 * Class Mail
 * @package App\Helper
 */
class Mail
{
    /**
     * Method for sending mail with PHPMailer.
     * @param string $body
     */
    public static function send($body){
        $mail               = new PHPMailer(); // create a new object
        $mail->IsSMTP(); // enable SMTP
//        $mail->SMTPDebug    = 1; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth     = true; // authentication enabled
        $mail->SMTPSecure   = 'ssl'; // secure transfer enabled REQUIRED for Gmail
        $mail->Host         = "smtp.gmail.com";
        $mail->Port         = 465; // or 587
        $mail->IsHTML(true);
        $mail->Username     = "test.pim8@gmail.com";
        $mail->Password     = "TestPim78";
        $mail->SetFrom("test.pim8@gmail.com");
        $mail->Subject      = "Test";
        $mail->Body         = $body;
        $mail->AddAddress("draganm78@ptt.rs");
        $mail->send();
	}
}