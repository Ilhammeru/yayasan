<?php

namespace App\Http\Services;

use PHPMailer\PHPMailer\PHPMailer;

class EmailService {
    public function init()
    {
        $config = [
            'name' => 'Yayasan',
            'email' => 'gumilang.dev@gmail.com',
            'host' => env('MAIL_HOST', 'smtp.gmail.com'),
            'port' => env('MAIL_PORT', 465),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'encryption' => env('MAIL_ENCRYPTION'),
        ];

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = $config['host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $config['username'];
        $mail->Password   = $config['password'];
        if ($config['encryption'] == 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        }else{
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        }
        $mail->Port       = $config['port'];
        $mail->CharSet = 'UTF-8';

        return $mail;
    }

    public function send($receiver, $theme)
    {
        $template = $this->get_mail_theme($theme);

        $mail = $this->init();

        try {
            //Recipients
            // $mail->setFrom($config['email'], $config['name']);
            $mail->addAddress($receiver->email, $receiver->name);
            // $mail->addReplyTo($config['email'], $config['name']);
            // Content
            $mail->isHTML(true);
            $mail->Subject = $template['subject'];
            $mail->Body    = $template['view'];
            $mail->send();
        } catch ( \Throwable $e) {
            return $e->getMessage();
        }
    }

    public function get_mail_theme($theme)
    {
        if ($theme == 'approved_proposal') {
            $view = view('email_templates.' . $theme)->render();
            $subject = 'Proposal Disetujui';
        }

        return [
            'subject' => $subject,
            'view' => $view,
        ];
    }
}