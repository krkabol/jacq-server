<?php

declare(strict_types=1);

namespace app\Model;

use app\Services\S3Service;
use app\Services\TempDir;

class PhotoOfSpecimen
{

    private string $sourceBucket;
    private string $objectName;
    private S3Service $s3Service;
    private TempDir $tempDir;

    private int $height;
    private int $width;


    public function __construct(string $bucket, string $objectName, S3Service $s3Service, TempDir $tempDir)
    {
        $this->sourceBucket = $bucket;
        $this->objectName = $objectName;
        $this->s3Service = $s3Service;
        $this->tempDir = $tempDir;
        $this->s3Service->getObject($this->sourceBucket, $this->objectName, $this->getTempfile());
    }

    public function getTempfile()
    {
        return $this->tempDir->getPath($this->objectName);
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



}
