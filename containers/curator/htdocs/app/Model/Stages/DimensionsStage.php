<?php

declare(strict_types=1);

namespace app\Model\Stages;

use app\Model\PhotoOfSpecimen;
use League\Pipeline\StageInterface;

class DimensionStageException extends BaseStageException
{

}

class DimensionsStage implements StageInterface
{
    public function __invoke($payload)
    {
        try {
            /** @var PhotoOfSpecimen $payload */
            $imagick = $payload->getImagick();
            $payload->setWidth($imagick->getImageWidth());
            $payload->setHeight($imagick->getImageHeight());
        } catch (\Exception $exception) {
            throw new DimensionStageException("dimensions error (" . $exception->getMessage() . "): " . $payload->getObjectKey());
        }
        return $payload;
    }
}
