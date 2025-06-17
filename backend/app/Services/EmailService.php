<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



class EmailService
{
    public function sendVerificationEmail($user)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();   
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = env('MAIL_USERNAME');  // You can store this in .env file
            $mail->Password   = env('MAIL_PASSWORD');  // You can store this in .env file
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Set sender
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), 'StyleOra');
            // Set recipient
            $mail->addAddress($user->email, $user->name);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Email Verification From StyleOra';

            // Generate the verification link with the user ID and email hash
            $verificationUrl = route('verify.email', [
                'id' => $user->id,
                'hash' => sha1($user->getEmailForVerification())
            ]);

            $email_html = "
            <body style=\"font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;\">
                <h1 style=\"color: #333333; text-align: center;\">Welcome to StyleOra!</h1>
                <p style=\"text-align: center;\">Please click the button below to verify your email:</p>
                <p style=\"text-align: center;\"><a href=\"{$verificationUrl}\" style=\"background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;\">Click here to verify your email</a></p>
            </body>";


            $mail->Body = $email_html;
            $mail->send();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
