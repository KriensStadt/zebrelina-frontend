<?php

declare(strict_types=1);

namespace App\Controller\Admin\Admin;

use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/admin', name: 'admin.admin.index')]
class Index extends AbstractController
{
    public function __construct(
        private readonly AdminRepository $userRepository,
    ) {
    }

    public function __invoke(): Response
    {
        $admins = $this->userRepository->findAllForAdminOverview();

        return $this->render('admin/admin/index.html.twig', [
            'admins' => $admins,
        ]);
    }
}
