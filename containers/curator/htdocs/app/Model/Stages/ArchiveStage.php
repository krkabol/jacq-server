<?php

declare(strict_types=1);

namespace app\Model\Stages;

use app\Model\PhotoOfSpecimen;
use League\Pipeline\StageInterface;

class ArchiveStage implements StageInterface
{

    public function __invoke($payload)
    {
        /** @var PhotoOfSpecimen $payload */
        $payload->putTiff();
        return $payload;
    }


}
