<?php

declare(strict_types=1);

namespace App\Controller\Admin\Device;

use App\Entity\Device;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/device/{device}/delete', name: 'admin.device.delete')]
class Delete extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(Device $device): Response
    {
        $this->addFlash('success', 'Removed device');

        $this->entityManager->remove($device);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin.device.index');
    }
}
