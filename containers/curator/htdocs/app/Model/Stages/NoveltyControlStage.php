<?php

declare(strict_types=1);

namespace app\Model\Stages;

use app\Model\PhotoOfSpecimen;
use app\Services\S3Service;
use app\UI\Home\HomePresenter;
use League\Pipeline\StageInterface;

class NoveltyControlException extends BaseStageException
{

}

class NoveltyControlStage implements StageInterface
{

    private S3Service $s3Service;

    public function __construct(S3Service $s3Service)
    {
        $this->s3Service = $s3Service;
    }

    public function __invoke($payload)
    {
        /** @var PhotoOfSpecimen $payload */
        if ($this->s3Service->objectExists(HomePresenter::ARCHIVE_BUCKET, $payload->getObjectKey())) {
            throw new NoveltyControlException("Archive master TIF already exists: " . $payload->getObjectKey());
        }
        return $payload;
    }


}
