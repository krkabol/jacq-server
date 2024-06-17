<?php

declare(strict_types=1);

namespace app\Model\Stages;


use League\Pipeline\StageInterface;

class BarcodeStage implements StageInterface
{

    public function __invoke($payload)
    {
        return $payload;
    }


}
