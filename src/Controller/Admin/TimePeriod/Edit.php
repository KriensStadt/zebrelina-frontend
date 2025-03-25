<?php

declare(strict_types=1);

namespace App\Controller\Admin\TimePeriod;

use App\Entity\TimePeriod;
use App\Form\TimePeriodType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/admin/time-period/{timePeriod}/edit',
    name: 'admin.time_period.edit',
    defaults: [
        '_menu' => 'admin.time_period',
    ]
)]
class Edit extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(Request $request, TimePeriod $timePeriod): Response
    {
        $form = $this->createForm(TimePeriodType::class, $timePeriod);
        $form->handleRequest($request);

        $showExport = $timePeriod->getPeriodEnd() < new \DateTime() && !$timePeriod->isActive();

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($timePeriod);
            $this->entityManager->flush();

            $this->addFlash('success', 'Edited time period');

            return $this->redirectToRoute('admin.time_period.index');
        }

        return $this->render('admin/time_period/edit.html.twig', [
            'form' => $form,
            'timePeriod' => $timePeriod,
            'showExport' => $showExport,
        ]);
    }
}
