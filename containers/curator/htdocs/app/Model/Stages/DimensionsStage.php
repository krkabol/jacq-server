<?php

declare(strict_types=1);

namespace app\Model\Stages;

use app\Model\PhotoOfSpecimen;
use app\Services\S3Service;
use app\UI\Home\HomePresenter;
use League\Pipeline\StageInterface;

class DimensionStageException extends BaseStageException
{

}

class DimensionsStage implements StageInterface
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
            $imagick = $payload->getImagick();
            $payload->setWidth($imagick->getImageWidth());
            $payload->setHeight($imagick->getImageHeight());
            $payload->setTiffSize($this->s3Service->getObjectSize(HomePresenter::START_BUCKET, $payload->getObjectKey()));
        } catch (\Exception $exception) {
            throw new DimensionStageException("dimensions error (" . $exception->getMessage() . "): " . $payload->getObjectKey());
        }
        return $payload;
    }
}
