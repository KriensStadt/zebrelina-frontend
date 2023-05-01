<?php

declare(strict_types=1);

namespace App\Controller\Admin\TimePeriod;

use App\Entity\TimePeriod;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/time-period/{timePeriod}/delete', name: 'admin.time_period.delete')]
class Delete extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(TimePeriod $timePeriod): Response
    {
        $this->addFlash('success', 'Removed time period');

        $this->entityManager->remove($timePeriod);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin.time_period.index');
    }
}
