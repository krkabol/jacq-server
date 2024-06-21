<?php

declare(strict_types=1);

namespace app\Model\Stages;

use app\Model\PhotoOfSpecimen;
use app\Services\S3Service;
use app\UI\Home\HomePresenter;
use Exception;
use League\Pipeline\StageInterface;

class ConvertStageException extends BaseStageException
{

}

class ConvertStage implements StageInterface
{
    private S3Service $s3Service;

    public function __construct(S3Service $s3Service)
    {
        $this->s3Service = $s3Service;
    }


    public function __invoke($payload)
    {
        /** @var PhotoOfSpecimen $payload */
        try {
            $imagick = $payload->getImagick();
            $imagick->setImageFormat('jp2');
            $imagick->writeImage($payload->getJP2Fullname());
            $this->s3Service->putJP2Overwrite(HomePresenter::JP2_BUCKET, $payload->getJP2ObjectKey(), $payload->getJP2Fullname());
            $payload->setJp2Size($this->s3Service->getObjectSize(HomePresenter::JP2_BUCKET, $payload->getJP2ObjectKey()));
            unlink($payload->getJP2Fullname());
        } catch (Exception $exception) {
            throw new ConvertStageException("unable convert to JP2 (" . $exception->getMessage() . "): " . $payload->getObjectKey());
        }
        return $payload;
    }


}
