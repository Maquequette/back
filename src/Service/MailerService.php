<?php

// src/Controller/MailerController.php
namespace App\Service;

 
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\RawMessage;
use App\Service\Mailer;
use Symfony\Component\Mailer\MailerInterface;

class MailerService 
{
    private MailerInterface $mailer;
    private string $from;
    public function __construct(
        MailerInterface $MAILER,
        string $SENDER,
    ){
        $this->mailer=$MAILER;
        $this->from=$SENDER;
    }
    public function sendEmailToSomeone(string $someone,string $subject,string $content  ): void
    {
        $email = (new Email())
            ->from($this->from)
            ->to($someone)
            ->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
            ->text($content)
            ->html(
        '<!DOCTYPE html>
            <html>
              <head>
                <title>Mon Header</title>
                <style>
                  body {
                    background-color: #f5f5f7;
                    display: flex;
                    align-items: center;
                    flex-direction: column;
                  }
                  header {
                    background-color: #ffde79;
                    display: flex;
                    align-items: center;
                    width: 100%;
                  }
            
                  header h1 {
                    margin: auto;
                    color: #000;
                    font-feature-settings: "dlig" on;
                    font-family: Space Grotesk;
                    font-size: 32px;
                    font-style: normal;
                    font-weight: 700;
                    line-height: 54px;
                  }
                </style>
              </head>
              <body>
                <header>
                <img height="50px" style="display:block" title="Logo" width="50px "src="https://maquequettefile.s3.eu-west-3.amazonaws.com/logo-app.png" alt="icon maquequettefile   ">

                  <h1>Mac&Kate</h1>
                </header>
                <div style="text-align: center;">
                    <h1>Merci de votre inscription</h1>
                </div>
              </body>
            </html>
            ');
            //Je signale que je n'ai pas envie de recevoir un mail automatique si jamais le destinataire en renvoie
            $headers = $email->getHeaders();
            $headers->addTextHeader('X-Auto-Response-Suppress', 'OOF, DR, RN, NRN, AutoReply');
            

        $this->mailer->send($email);
    }
}