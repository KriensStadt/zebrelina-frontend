<?php

declare(strict_types=1);

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/{sensor}', name: 'sensor')]
class Sensor extends AbstractController
{
    public function __construct(
        private readonly Connection $database,
    ) {
    }

    public function __invoke(string $sensor): Response
    {
        $sensorExists = $this->database->executeQuery('SELECT 1 FROM metrics WHERE device_id = :sensor LIMIT 1', [
            'sensor' => $sensor,
        ]);

        if (false === $sensorExists->fetchOne()) {
            throw $this->createNotFoundException(sprintf('Sensor "%s" not found', $sensor));
        }

        $dataPoints = $this->database->executeQuery('
            SELECT
                time,
                ST_X(location::geometry) AS long,
                ST_Y(location::geometry) AS lat
            FROM metrics
            WHERE device_id = :sensor
            ORDER BY time DESC
        ', [
            'sensor' => $sensor,
        ])->fetchAllAssociative();

        return $this->render('sensor.html.twig', [
            'sensor' => $sensor,
            'dataPoints' => $dataPoints,
        ]);
    }
}
