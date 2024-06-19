<?php

declare(strict_types=1);

namespace app\Model\Stages;

use app\Model\PhotoOfSpecimen;
use League\Pipeline\StageInterface;

class DimensionsStage implements StageInterface
{
    public function __invoke($payload)
    {
        /** @var PhotoOfSpecimen $payload */
        $imagick = $payload->getImagick();
        $payload->setWidth($imagick->getImageWidth());
        $payload->setHeight($imagick->getImageHeight());
        return $payload;
    }
}
