<?php

declare(strict_types=1);

namespace App\Controller\Admin\Group;

use App\Entity\DeviceGroup;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/group/{group}/delete', name: 'admin.group.delete')]
class Delete extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(DeviceGroup $group): Response
    {
        $this->addFlash('success', 'Removed group');

        $this->entityManager->remove($group);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin.group.index');
    }
}
