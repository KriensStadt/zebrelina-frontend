<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

#[AsEventListener(event: LoginSuccessEvent::class)]
class PostLoginRedirectListener
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly Security $security,
    ) {
    }

    public function __invoke(LoginSuccessEvent $event): void
    {
        if ($this->security->isGranted('ROLE_DEVICE')) {
            $event->setResponse(new RedirectResponse($this->router->generate('device.index')));
        }

        if ($this->security->isGranted('ROLE_GROUP')) {
            $event->setResponse(new RedirectResponse($this->router->generate('group.index')));
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $event->setResponse(new RedirectResponse($this->router->generate('admin.index')));
        }
    }
}
