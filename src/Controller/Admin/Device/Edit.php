<?php

declare(strict_types=1);

namespace App\Controller\Admin\Device;

use App\Entity\Device;
use App\Form\DeviceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/admin/device/{device}/edit',
    name: 'admin.device.edit',
    defaults: [
        '_menu' => 'admin.device',
    ]
)]
class Edit extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function __invoke(Request $request, Device $device): Response
    {
        $form = $this->createForm(DeviceType::class, $device);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string|null $password */
            $password = $form->get('password')->getData();

            if ($password) {
                $device->setPassword($this->passwordHasher->hashPassword($device, $password));
            }

            $this->entityManager->persist($device);
            $this->entityManager->flush();

            $this->addFlash('success', 'Edited device');

            return $this->redirectToRoute('admin.device.index');
        }

        return $this->render('admin/device/edit.html.twig', [
            'form' => $form,
            'device' => $device
        ]);
    }
}
