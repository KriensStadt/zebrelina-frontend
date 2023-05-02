<?php

declare(strict_types=1);

namespace App\Controller\Admin\CommentType;

use App\Entity\CommentType;
use App\Form\CommentTypeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/admin/comment-type/{commentType}/edit',
    name: 'admin.comment_type.edit',
    defaults: [
        '_menu' => 'admin.comment_type',
    ]
)]
class Edit extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(Request $request, CommentType $commentType): Response
    {
        $form = $this->createForm(CommentTypeType::class, $commentType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($commentType);
            $this->entityManager->flush();

            $this->addFlash('success', 'Edited comment type');

            return $this->redirectToRoute('admin.comment_type.index');
        }

        return $this->render('admin/comment_type/edit.html.twig', [
            'form' => $form,
            'commentType' => $commentType,
        ]);
    }
}
