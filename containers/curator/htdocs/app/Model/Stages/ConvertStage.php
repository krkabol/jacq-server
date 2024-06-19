<?php

declare(strict_types=1);

namespace app\Model\Stages;

use app\Model\PhotoOfSpecimen;
use League\Pipeline\StageInterface;

class ConvertStage implements StageInterface
{

    public function __invoke($payload)
    {
         /** @var PhotoOfSpecimen $payload */
         $imagick = $payload->getImagick();
         $imagick->setImageFormat('jp2');
         $imagick->writeImage($payload->getJP2Fullname());
         $payload->putJP2();
        return $payload;
    }


}
