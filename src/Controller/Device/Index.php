<?php

declare(strict_types=1);

namespace App\Controller\Device;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/device', name: 'device.index')]
class Index extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('/device/index.html.twig');
    }
}
