<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\DataPoint;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class PolylineGenerator
{
    public function __construct(
        #[Autowire('%app.polyline_group_time%')]
        private readonly int $polylineGroupTime,
    ) {
    }

    public function createLines(array $points): array
    {
        $polylines = [];

        /** @var DataPoint $point */
        foreach ($points as $point) {
            $groupName = (string) $point->getGroup();

            if (!\array_key_exists($groupName, $polylines)) {
                $polylines[$groupName] = [];
            }

            $polylines[$groupName][] = $point;
        }

        $timeGroupedPolylines = [];
        $polylines = array_values($polylines);

        $currentGroup = 0;

        foreach ($polylines as $group) {
            /** @var DataPoint $point */
            foreach ($group as $point) {
                if (!array_key_exists($currentGroup, $timeGroupedPolylines)) {
                    $timeGroupedPolylines[$currentGroup] = [];
                }

                /** @var false|DataPoint $lastPoint */
                $lastPoint = end($timeGroupedPolylines[$currentGroup]);

                if ($lastPoint) {
                    if ($point->getCreated()->getTimestamp() - $lastPoint->getCreated()->getTimestamp() > $this->polylineGroupTime) {
                        $currentGroup += 1;
                    }
                }

                if (!array_key_exists($currentGroup, $timeGroupedPolylines)) {
                    $timeGroupedPolylines[$currentGroup] = [];
                }

                $timeGroupedPolylines[$currentGroup][] = $point;
            }
        }

        return $timeGroupedPolylines;
    }
}
