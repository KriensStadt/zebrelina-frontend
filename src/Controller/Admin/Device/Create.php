<?php

declare(strict_types=1);

namespace App\Controller\Admin\Device;

use App\Entity\Device;
use App\Form\DeviceType;
use App\Service\AutoApprover;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/admin/device/create',
    name: 'admin.device.create',
    defaults: [
        '_menu' => 'admin.device',
    ]
)]
class Create extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly AutoApprover $approver,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $device = new Device();

        $form = $this->createForm(DeviceType::class, $device);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $password */
            $password = $form->get('password')->getData();

            $device->setPassword($this->passwordHasher->hashPassword($device, $password));

            $this->approver->createApprovalsForDevice($device);

            $this->entityManager->persist($device);
            $this->entityManager->flush();

            $this->addFlash('success', 'Created device');

            return $this->redirectToRoute('admin.device.index');
        }

        return $this->render('admin/device/create.html.twig', [
            'form' => $form,
            'device' => $device
        ]);
    }
}
