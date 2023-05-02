<?php

declare(strict_types=1);

namespace App\Controller\Admin\CommentType;

use App\Repository\CommentTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/comment-type', name: 'admin.comment_type.index')]
class Index extends AbstractController
{
    public function __construct(
        private readonly CommentTypeRepository $commentTypeRepository,
    ) {
    }

    public function __invoke(): Response
    {
        $commentTypes = $this->commentTypeRepository->findAllForAdminOverview();

        return $this->render('admin/comment_type/index.html.twig', [
            'commentTypes' => $commentTypes,
        ]);
    }
}
