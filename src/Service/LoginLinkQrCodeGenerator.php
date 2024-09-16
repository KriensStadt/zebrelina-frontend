<?php

declare(strict_types=1);

namespace App\Service;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

class LoginLinkQrCodeGenerator
{
    public function generate(string $url, ?string $password, ?string $deviceName = null): string
    {
        $builder = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($url)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(300)
            ->margin(10)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->validateResult(false)
        ;

        $labelParts = [];

        if (null !== $deviceName) {
            $labelParts[] = $deviceName;
        }

        if (count($labelParts) > 0) {
            $builder
                ->labelText(implode(' / ', $labelParts))
                ->labelAlignment(new LabelAlignmentCenter())
            ;
        }

        $result = $builder->build();

        return $result->getDataUri();
    }
}
