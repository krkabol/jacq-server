<?php

declare(strict_types=1);

namespace app\Model;

use app\Services\S3Service;
use app\Services\TempDir;
use app\UI\Home\HomePresenter;
use Imagick;


class PhotoOfSpecimen
{

    private string $sourceBucket;
    private string $objectKey;
    private S3Service $s3Service;
    private TempDir $tempDir;

    private bool $isDownloaded = false;
    private ?Imagick $imagick = null;

    private int $height;
    private int $width;
    private string $herbariumAcronym;
    private string $specimenId;


    public function __construct(string $bucket, string $objectKey, S3Service $s3Service, TempDir $tempDir)
    {
        $this->sourceBucket = $bucket;
        $this->objectKey = $objectKey;
        $this->s3Service = $s3Service;
        $this->tempDir = $tempDir;
    }

    public function getImagick(): Imagick
    {
        if ($this->imagick === null) {
            $this->imagick = new Imagick($this->getTempfile());
        }

        return $this->imagick;
    }

    public function getTempfile()
    {
        $this->downloadFromS3();
        return $this->getTempfileName();
    }

    private function downloadFromS3(): PhotoOfSpecimen
    {
        if (!$this->isDownloaded) {
            $this->s3Service->getObject($this->sourceBucket, $this->objectKey, $this->getTempfileName());
            $this->isDownloaded = true;
        }
        return $this;
    }

    private function getTempfileName()
    {
        return $this->tempDir->getPath($this->getObjectKey());
    }

    public function getObjectKey(): string
    {
        return $this->objectKey;
    }

    public function putJP2(): void
    {
        $this->s3Service->putJP2Overwrite(HomePresenter::JP2_BUCKET, $this->getJP2ObjectKey(), $this->getJP2Fullname());
    }

    public function putArchiveTiff(): void
    {
        $this->s3Service->copyObjectIfNotExists($this->getObjectKey(), HomePresenter::START_BUCKET, HomePresenter::ARCHIVE_BUCKET);
    }

    public function getJP2ObjectKey(): string
    {
        return str_replace("tif", "jp2", $this->getObjectKey());
    }

    public function getJP2Fullname(): string
    {
        return $this->tempDir->getPath($this->getJP2ObjectKey());
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setHeight(int $height): PhotoOfSpecimen
    {
        $this->height = $height;
        return $this;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function setWidth(int $width): PhotoOfSpecimen
    {
        $this->width = $width;
        return $this;
    }


    public function getHerbariumAcronym(): string
    {
        return $this->herbariumAcronym;
    }

    public function setHerbariumAcronym(string $acronym): PhotoOfSpecimen
    {
        $this->herbariumAcronym = $acronym;
        return $this;
    }

    public function getSpecimenId(): string
    {
        return $this->specimenId;
    }

    public function setSpecimenId(string $id): PhotoOfSpecimen
    {
        $this->specimenId = $id;
        return $this;
    }

    public function unsetImagick()
    {
        unset($this->imagick);
        return $this;
    }
}
