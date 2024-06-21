<?php

declare(strict_types=1);

namespace app\Model\Stages;


use app\Model\PhotoOfSpecimen;
use app\Services\S3Service;
use app\UI\Home\HomePresenter;
use League\Pipeline\StageInterface;

class CleanupStageException extends BaseStageException
{

}

class CleanupStage implements StageInterface
{

    private S3Service $s3Service;

    public function __construct(S3Service $s3Service)
    {
        $this->s3Service = $s3Service;
    }

    public function __invoke($payload)
    {
        try {
            /** @var PhotoOfSpecimen $payload */
            $payload->unsetImagick();
            unlink($payload->getTempfile());
            $this->s3Service->deleteObject(HomePresenter::START_BUCKET, $payload->getObjectKey());
        } catch (\Exception $exception) {
            throw new CleanupStageException("cleanup error (" . $exception->getMessage() . "): " . $payload->getObjectKey());
        }
        return $payload;
    }

}
