<?php

declare(strict_types=1);

namespace App\Controller\Admin\Admin;

use App\Entity\Admin;
use App\Form\AdminType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/admin/create', name: 'admin.admin.create')]
class Create extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $admin = new Admin();

        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string|null $password */
            $password = $form->get('password')->getData();

            $admin->setPassword($this->passwordHasher->hashPassword($admin, $password));

            $this->entityManager->persist($admin);
            $this->entityManager->flush();

            $this->addFlash('success', 'Created admin user');

            return $this->redirectToRoute('admin.admin.index');
        }

        return $this->render('admin/admin/create.html.twig', [
            'form' => $form,
            'admin' => $admin
        ]);
    }
}
