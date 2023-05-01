<?php

declare(strict_types=1);

namespace App\Controller\Admin\Group;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/group', name: 'admin.group.index')]
class Index extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('admin/group/index.html.twig');
    }
}
