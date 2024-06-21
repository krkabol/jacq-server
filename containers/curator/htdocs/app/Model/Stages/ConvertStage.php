<?php

declare(strict_types=1);

namespace app\Model\Stages;

use app\Model\PhotoOfSpecimen;
use Exception;
use League\Pipeline\StageInterface;

class ConvertStageException extends BaseStageException
{

}

class ConvertStage implements StageInterface
{

    public function __invoke($payload)
    {
        /** @var PhotoOfSpecimen $payload */
        try {
            $imagick = $payload->getImagick();
            $imagick->setImageFormat('jp2');
            $imagick->writeImage($payload->getJP2Fullname());
            $payload->putJP2();
            unlink($payload->getJP2Fullname());
        } catch (Exception $exception) {
            throw new ConvertStageException("unable convert to JP2 (" . $exception->getMessage() . "): " . $payload->getObjectKey());
        }
        return $payload;
    }


}
