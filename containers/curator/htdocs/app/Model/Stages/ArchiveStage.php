<?php

declare(strict_types=1);

namespace app\Model\Stages;

use app\Model\PhotoOfSpecimen;
use app\Services\S3Service;
use app\UI\Home\HomePresenter;
use League\Pipeline\StageInterface;

class ArchiveStageException extends BaseStageException
{

}

class ArchiveStage implements StageInterface
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
            $this->s3Service->copyObjectIfNotExists($payload->getObjectKey(), HomePresenter::START_BUCKET, HomePresenter::ARCHIVE_BUCKET);
        } catch (\Exception $exception) {
            throw new ArchiveStageException("tiff upload error (" . $exception->getMessage() . "): " . $payload->getObjectKey());
        }
        return $payload;
    }


}
