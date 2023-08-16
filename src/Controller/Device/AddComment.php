<?php

declare(strict_types=1);

namespace App\Controller\Device;

use App\Entity\Comment;
use App\Entity\Device;
use App\Form\CommentType;
use App\Repository\ApprovalRepository;
use App\Service\TimePeriodProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/device/add-comment/{lat}/{lng}/{date}', name: 'device.add_comment', requirements: ['date' => '\d+-\d+-\d+'], defaults: ['date' => null])]
class AddComment extends AbstractController
{
    public function __construct(
        private readonly TimePeriodProvider $timePeriodProvider,
        private readonly ApprovalRepository $approvalRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(Request $request, float $lat, float $lng, ?string $date): Response
    {
        $lng = round($lng, 5);
        $lat = round($lat, 5);

        /** @var Device $device */
        $device = $this->getUser();

        $timePeriod = $this->timePeriodProvider->getTimePeriod();
        $approval = $this->approvalRepository->findOneByDeviceAndTimePeriod($device, $timePeriod);

        if (null === $approval->getId()) {
            throw new NotFoundHttpException();
        }

        $comment = new Comment();
        $comment->setApproval($approval);
        $comment->setPoint(sprintf('POINT(%s %s)', $lng, $lat));

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->redirectToRoute('device.index', [
                'date' => $date,
            ]);
        }

        return $this->render('device/add_comment.html.twig', [
            'comment' => $comment,
            'form' => $form,
            'lat' => $lat,
            'lng' => $lng,
            'timePeriod' => $timePeriod,
        ]);
    }
}
