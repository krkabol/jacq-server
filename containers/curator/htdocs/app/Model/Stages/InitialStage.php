<?php

declare(strict_types=1);

namespace app\Model\Stages;


use app\Model\PhotoOfSpecimen;
use League\Pipeline\StageInterface;

class InitialStage implements StageInterface
{

    public function __invoke($payload)
    {
        /** @var PhotoOfSpecimen $payload */
        $payload->downloadFromS3();
        return $payload;
    }


}
