<?php

declare(strict_types=1);

namespace App\Controller\Admin\TimePeriod;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/time-period', name: 'admin.time_period.index')]
class Index extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('admin/time_period/index.html.twig');
    }
}
