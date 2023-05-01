<?php

declare(strict_types=1);

namespace App\Controller\Admin\Group;

use App\Entity\DeviceGroup;
use App\Form\DeviceGroupType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/admin/group/create',
    name: 'admin.group.create',
    defaults: [
        '_menu' => 'admin.group',
    ]
)]
class Create extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $group = new DeviceGroup();

        $form = $this->createForm(DeviceGroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $password */
            $password = $form->get('password')->getData();

            $group->setPassword($this->passwordHasher->hashPassword($group, $password));

            $this->entityManager->persist($group);
            $this->entityManager->flush();

            $this->addFlash('success', 'Created group');

            return $this->redirectToRoute('admin.group.index');
        }

        return $this->render('admin/group/create.html.twig', [
            'form' => $form,
            'group' => $group
        ]);
    }
}
