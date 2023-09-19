<?php
namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\User;
use App\Service\MailerService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class EventSucriberPost implements EventSubscriberInterface
{
    public function __construct(private readonly MailerService $mailer,) {}

    public static function getSubscribedEvents() {
        return [
            KernelEvents::VIEW => ['afterPost', EventPriorities::POST_WRITE],
        ];
    }

    public function afterPost(ViewEvent $event): void {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$user instanceof User || Request::METHOD_POST !== $method) {
            return;
        }

        $this->mailer->sendEmailToSomeone( $user->getEmail(),"Activation compte" ,`Bonjour ` );
    }
}