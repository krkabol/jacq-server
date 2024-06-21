<?php

declare(strict_types=1);

namespace app\Model\Stages;


use app\Model\PhotoOfSpecimen;
use League\Pipeline\StageInterface;

class CleanupStageException extends BaseStageException
{

}
class CleanupStage implements StageInterface
{

    public function __invoke($payload)
    {
        try {
            /** @var PhotoOfSpecimen $payload */
            $payload->unsetImagick();
        unlink($payload->getTempfile());
        }catch (\Exception $exception){
            throw new CleanupStageException("cleanup error (".$exception->getMessage()."): ".$payload->getObjectKey());
        }
        return $payload;
    }

}
