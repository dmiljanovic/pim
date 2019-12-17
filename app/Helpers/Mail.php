<?php

namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

/**
 * Class Mail
 * @package App\Helper
 */
class Mail
{
    /**
     * Method for sending mail with PHPMailer.
     */
    public static function send(){
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPAuth = true;
        $mail->Username = 'test.pim8@gmail.com';
        $mail->Password = 'TestPim78';
        $mail->setFrom('from@example.com', 'First Last');
        $mail->addReplyTo('replyto@example.com', 'First Last');
        $mail->addAddress('draganm78@ptt.mail', 'Test PIM');
        $mail->Subject = 'PHPMailer GMail SMTP test';
        $mail->msgHTML('GBP: Send an email with order details. This can be a basic text or html email to any configurable email address.');
        $mail->AltBody = 'This is a plain-text message body';
        $mail->send();
	}
}