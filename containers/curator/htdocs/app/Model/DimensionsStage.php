<?php

declare(strict_types=1);

namespace app\Model;


use app\Services\TempDir;
use League\Pipeline\StageInterface;

class DimensionsStage implements StageInterface
{

    public function __invoke($payload)
    {
        /** @var PhotoOfSpecimen $payload */
        $imagick = new \Imagick($payload->getTempfile());
        $payload->setWidth($imagick->getImageWidth());
        $payload->setHeight($imagick->getImageHeight());
        return $payload;
    }


}
