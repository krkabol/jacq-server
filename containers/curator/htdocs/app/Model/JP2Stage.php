<?php

declare(strict_types=1);

namespace app\Model;


use League\Pipeline\StageInterface;

class JP2Stage implements StageInterface
{

    public function __invoke($payload)
    {
        return $payload;
    }


}
