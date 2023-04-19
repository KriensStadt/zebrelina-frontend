<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\RemoteDeviceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/asdf', name: 'index')]
class Index extends AbstractController
{
    public function __construct(
        private readonly RemoteDeviceRepository $repository,
    ) {
    }

    public function __invoke(): Response
    {
        $devices = $this->repository->getDeviceIds();

        return $this->render('index.html.twig', [
            'devices' => $devices,
        ]);
    }
}
