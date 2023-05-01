<?php

declare(strict_types=1);

namespace App\Controller\Admin\TimePeriod;

use App\Repository\TimePeriodRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/time-period', name: 'admin.time_period.index')]
class Index extends AbstractController
{
    public function __construct(
        private readonly TimePeriodRepository $timePeriodRepository,
    ) {
    }

    public function __invoke(): Response
    {
        $timePeriods = $this->timePeriodRepository->findAllForAdminOverview();

        return $this->render('admin/time_period/index.html.twig', [
            'timePeriods' => $timePeriods,
        ]);
    }
}
