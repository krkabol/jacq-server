<?php

declare(strict_types=1);

namespace app\Model\Stages;


use League\Pipeline\StageInterface;

class CleanupStage implements StageInterface
{

    public function __invoke($payload)
    {
        //TODO delete tif+jp2 tem files, destroy imagick object
        return $payload;
    }


}
