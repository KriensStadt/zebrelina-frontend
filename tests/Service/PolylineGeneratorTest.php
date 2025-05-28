<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Model\DataPoint;
use App\Service\PolylineGenerator;
use PHPUnit\Framework\TestCase;

class PolylineGeneratorTest extends TestCase
{
    public function testReturnsEmptyArray(): void
    {
        $generator = new PolylineGenerator(10);

        $this->assertEmpty($generator->createLines([]));
    }

    public function testReturnsSingleGroupForSingleElement(): void
    {
        $group = [
            new DataPoint(1.0, 1.0, new \DateTimeImmutable('now'))
        ];

        $generator = new PolylineGenerator(10);

        $polylines = $generator->createLines($group);

        $this->assertCount(1, $polylines);
        $this->assertCount(1, $polylines[0]);
    }

    public function testReturnsTwoGroupsForSpacedElements(): void
    {
        $group = [
            new DataPoint(1.0, 1.0, new \DateTimeImmutable('yesterday')),
            new DataPoint(1.0, 1.0, new \DateTimeImmutable('now'))
        ];

        $generator = new PolylineGenerator(10);

        $polylines = $generator->createLines($group);

        $this->assertCount(2, $polylines);
        $this->assertCount(1, $polylines[0]);
        $this->assertCount(1, $polylines[1]);
    }

    public function testReturnsTwoGroupsForSpacedElementsWithLargeGroupTime(): void
    {
        $group = [
            new DataPoint(1.0, 1.0, new \DateTimeImmutable('yesterday')),
            new DataPoint(1.0, 1.0, new \DateTimeImmutable('now'))
        ];

        $generator = new PolylineGenerator(100000);

        $polylines = $generator->createLines($group);

        $this->assertCount(2, $polylines);
        $this->assertCount(1, $polylines[0]);
        $this->assertCount(1, $polylines[1]);
    }

    public function testReturnsCorrectGroupsForExample(): void
    {
        $group = [
            new DataPoint(1.0, 1.0, new \DateTimeImmutable('2023-08-21 07:04:41+00')),
            new DataPoint(1.0, 1.0, new \DateTimeImmutable('2023-08-21 07:19:18+00')),
            new DataPoint(1.0, 1.0, new \DateTimeImmutable('2023-08-21 07:31:14+00')),
            new DataPoint(1.0, 1.0, new \DateTimeImmutable('2023-08-21 07:43:12+00')),
            new DataPoint(1.0, 1.0, new \DateTimeImmutable('2023-08-21 07:55:10+00')),
            new DataPoint(1.0, 1.0, new \DateTimeImmutable('2023-08-21 08:18:19+00')),
            new DataPoint(1.0, 1.0, new \DateTimeImmutable('2023-08-21 08:30:15+00')),
            new DataPoint(1.0, 1.0, new \DateTimeImmutable('2023-08-21 08:42:14+00')),
            new DataPoint(1.0, 1.0, new \DateTimeImmutable('2023-08-21 08:54:13+00')),
            new DataPoint(1.0, 1.0, new \DateTimeImmutable('2023-08-21 09:06:10+00')),
            new DataPoint(1.0, 1.0, new \DateTimeImmutable('2023-08-21 09:18:09+00')),
            new DataPoint(1.0, 1.0, new \DateTimeImmutable('2023-08-21 09:30:07+00')),
            new DataPoint(1.0, 1.0, new \DateTimeImmutable('2023-08-21 09:42:06+00')),
            new DataPoint(1.0, 1.0, new \DateTimeImmutable('2023-08-21 09:54:04+00')),
            new DataPoint(1.0, 1.0, new \DateTimeImmutable('2023-08-21 10:06:02+00')),
            new DataPoint(1.0, 1.0, new \DateTimeImmutable('2023-08-21 10:08:41+00')),
            new DataPoint(1.0, 1.0, new \DateTimeImmutable('2023-08-21 10:20:39+00')),
            new DataPoint(1.0, 1.0, new \DateTimeImmutable('2023-08-21 10:36:10+00')),
            new DataPoint(1.0, 1.0, new \DateTimeImmutable('2023-08-21 10:44:37+00')),
            new DataPoint(1.0, 1.0, new \DateTimeImmutable('2023-08-21 11:10:24+00')),
        ];

        $generator = new PolylineGenerator(100000);

        $polylines = $generator->createLines($group);

        $this->assertCount(1, $polylines);
    }
}
