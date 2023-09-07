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
            ->text($content);
            //Je signale que je n'ai pas envie de recevoir un mail automatique si jamais le destinataire en renvoie
            $headers = $email->getHeaders();
            $headers->addTextHeader('X-Auto-Response-Suppress', 'OOF, DR, RN, NRN, AutoReply');
           // ->html('<p>See Twig integration for better HTML integration!</p>');

        $this->mailer->send($email);
    }
}