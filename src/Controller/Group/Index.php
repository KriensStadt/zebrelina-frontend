<?php

declare(strict_types=1);

namespace App\Controller\Group;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/group', name: 'group.index')]
class Index extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('/group/index.html.twig');
    }
}
