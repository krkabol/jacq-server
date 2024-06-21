<?php

declare(strict_types=1);

namespace app\Model\Stages;

use app\Model\PhotoOfSpecimen;
use League\Pipeline\StageInterface;

class ArchiveStageException extends BaseStageException
{

}

class ArchiveStage implements StageInterface
{

    public function __invoke($payload)
    {
        try {
            /** @var PhotoOfSpecimen $payload */
            $payload->putTiff();
        }catch (\Exception $exception){
            throw new ArchiveStageException("tiff upload error (".$exception->getMessage()."): ".$payload->getObjectKey());
        }
        return $payload;
    }


}
