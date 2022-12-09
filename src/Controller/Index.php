<?php

declare(strict_types=1);

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/', name: 'index')]
class Index extends AbstractController
{
    public function __construct(
        private readonly Connection $database,
    ) {
    }

    public function __invoke(): Response
    {
        $sensors = $this->database->executeQuery('SELECT DISTINCT device_id FROM metrics ORDER BY device_id')->fetchFirstColumn();

        return $this->render('index.html.twig', [
            'sensors' => $sensors,
        ]);
    }
}
