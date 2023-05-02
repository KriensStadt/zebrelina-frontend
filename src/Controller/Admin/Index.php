<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin', name: 'admin.index')]
class Index extends AbstractController
{
    public function __invoke(): RedirectResponse
    {
        return $this->redirectToRoute('admin.admin.index');
    }
}
