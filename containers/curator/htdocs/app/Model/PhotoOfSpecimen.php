<?php

declare(strict_types=1);

namespace app\Model;

use app\Services\S3Service;
use app\Services\TempDir;
use Imagick;


class PhotoOfSpecimen
{

    private string $sourceBucket;
    private string $objectName;
    private S3Service $s3Service;
    private TempDir $tempDir;

    private bool $isDownloaded = false;
    private ?Imagick $imagick = null;

    private int $height;
    private int $width;
    private string $herbariumAcronym;
    private string $specimenId;


    public function __construct(string $bucket, string $objectName, S3Service $s3Service, TempDir $tempDir)
    {
        $this->sourceBucket = $bucket;
        $this->objectName = $objectName;
        $this->s3Service = $s3Service;
        $this->tempDir = $tempDir;
    }

    public function getTempfile()
    {
        $this->downloadFromS3();
        return $this->getTempfileName();
    }

    public function getImagick() : Imagick {
        if ($this->imagick === null){        
            $this->imagick = new Imagick($this->getTempfile());
        }
        
        return $this->imagick;
    }

    private function getTempfileName()
    {
        return $this->tempDir->getPath($this->getObjectName());
    }

    public function getObjectName() : string {
        return $this->objectName;
    }

    public function downloadFromS3(): PhotoOfSpecimen
    {
        if (!$this->isDownloaded) {
            $this->s3Service->getObject($this->sourceBucket, $this->objectName, $this->getTempfileName());
            $this->isDownloaded = true;
        }
        return $this;
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

    public function setSpecimenId(string $id): PhotoOfSpecimen
    {
        $this->specimenId = $id;
        return $this;
    }
}
