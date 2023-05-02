<?php

declare(strict_types=1);

namespace App\Controller\Admin\CommentType;

use App\Entity\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/comment-type/{commentType}/delete', name: 'admin.comment_type.delete')]
class Delete extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(CommentType $commentType): Response
    {
        $this->addFlash('success', 'Removed comment type');

        $this->entityManager->remove($commentType);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin.comment_type.index');
    }
}
