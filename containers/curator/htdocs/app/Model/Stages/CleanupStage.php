<?php

declare(strict_types=1);

namespace app\Model\Stages;


use app\Model\PhotoOfSpecimen;
use League\Pipeline\StageInterface;

class CleanupStage implements StageInterface
{

    public function __invoke($payload)
    {
        /** @var PhotoOfSpecimen $payload */
        $payload->unsetImagick();
        unlink($payload->getTempfile());
        return $payload;
    }

}
