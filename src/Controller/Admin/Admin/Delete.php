<?php

declare(strict_types=1);

namespace App\Controller\Admin\Admin;

use App\Entity\Admin;
use App\Security\Voter\AdminVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/admin/admin/{admin}/delete', name: 'admin.admin.delete')]
#[IsGranted(AdminVoter::CAN_DELETE, 'admin')]
class Delete extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(Admin $admin): Response
    {
        $this->addFlash('success', 'Removed admin');

        $this->entityManager->remove($admin);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin.admin.index');
    }
}
