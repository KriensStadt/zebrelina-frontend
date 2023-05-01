<?php

declare(strict_types=1);

namespace App\Controller\Group;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/group/logout', name: 'group.logout')]
class Logout extends AbstractController
{
    public function __invoke(): Response
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
