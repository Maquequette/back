<?php
namespace App\EventSubscriber;
use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Challenge;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

use App\Service\MailerService;

final class ChallengeRegisterListener implements EventSubscriberInterface
{
    public  function __construct(private readonly MailerService $mailer) {
    }
    public static function getSubscribedEvents() :array
    {
        return [
            KernelEvents::VIEW => ['afterPost', EventPriorities::POST_WRITE]
        ];
    }
    public function afterPost(ViewEvent $event): void
    {
        $challenge = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$challenge instanceof Challenge || Request::METHOD_POST !== $method) {
            return;
        }
        $this->mailer->sendEmailToSomeone( "lyes.souifi@gmail.com", "toto", "qu'elle nom à toto");

    }
}