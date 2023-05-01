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
    path: '/admin/time-period/create',
    name: 'admin.time_period.create',
    defaults: [
        '_menu' => 'admin.time_period',
    ]
)]
class Create extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $timePeriod = new TimePeriod();

        $form = $this->createForm(TimePeriodType::class, $timePeriod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($timePeriod);
            $this->entityManager->flush();

            $this->addFlash('success', 'Created time period');

            return $this->redirectToRoute('admin.time_period.index');
        }

        return $this->render('admin/time_period/create.html.twig', [
            'form' => $form,
            'timePeriod' => $timePeriod
        ]);
    }
}
