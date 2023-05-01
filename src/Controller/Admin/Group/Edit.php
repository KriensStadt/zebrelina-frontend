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
    path: '/admin/group/{group}/edit',
    name: 'admin.group.edit',
    defaults: [
        '_menu' => 'admin.group',
    ]
)]
class Edit extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function __invoke(Request $request, DeviceGroup $group): Response
    {
        $form = $this->createForm(DeviceGroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string|null $password */
            $password = $form->get('password')->getData();

            if ($password) {
                $group->setPassword($this->passwordHasher->hashPassword($group, $password));
            }

            $this->entityManager->persist($group);
            $this->entityManager->flush();

            $this->addFlash('success', 'Edited group');

            return $this->redirectToRoute('admin.group.index');
        }

        return $this->render('admin/group/edit.html.twig', [
            'form' => $form,
            'group' => $group,
        ]);
    }
}
