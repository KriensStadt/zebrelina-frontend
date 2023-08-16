<?php

declare(strict_types=1);

namespace App\Controller\Group;

use App\Entity\Comment;
use App\Entity\DeviceGroup;
use App\Form\CommentType;
use App\Service\TimePeriodProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/group/add-comment/{lat}/{lng}/{date}', name: 'group.add_comment', requirements: ['date' => '\d+-\d+-\d+'], defaults: ['date' => null])]
class AddComment extends AbstractController
{
    public function __construct(
        private readonly TimePeriodProvider $timePeriodProvider,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(Request $request, float $lat, float $lng, ?string $date): Response
    {
        /** @var DeviceGroup $deviceGroup */
        $deviceGroup = $this->getUser();

        $lng = round($lng, 5);
        $lat = round($lat, 5);

        $timePeriod = $this->timePeriodProvider->getTimePeriod();

        $comment = new Comment();
        $comment->setTimePeriod($timePeriod);
        $comment->setDeviceGroup($deviceGroup);
        $comment->setPoint(sprintf('POINT(%s %s)', $lng, $lat));

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->redirectToRoute('group.index', [
                'date' => $date,
            ]);
        }

        return $this->render('group/add_comment.html.twig', [
            'comment' => $comment,
            'form' => $form,
            'lat' => $lat,
            'lng' => $lng,
            'timePeriod' => $timePeriod,
        ]);
    }
}
