<?php

declare(strict_types=1);

namespace app\Services;

use app\Model\DimensionsStage;
use app\Model\JP2Stage;
use app\Model\PhotoOfSpecimenFactory;
use app\UI\Home\HomePresenter;
use League\Pipeline\Pipeline;

class TestService
{
    private S3Service $S3Service;
    private WebDir $webDir;

    private PhotoOfSpecimenFactory $photoOfSpecimenFactory;

    public function __construct(S3Service $S3Service, WebDir $webDir, PhotoOfSpecimenFactory $photoOfSpecimenFactory)
    {
         $this->S3Service = $S3Service;
         $this->webDir = $webDir;
         $this->photoOfSpecimenFactory = $photoOfSpecimenFactory;
    }

    public function initialize(): void
    {
        foreach (HomePresenter::BUCKETS as $bucket) {
            $this->S3Service->createBucket($bucket);
        }

        $testDataDir = $this->webDir->getPath('data');
        foreach (HomePresenter::TEST_FILES as $file) {
            $this->S3Service->putTiffIfNotExists(HomePresenter::START_BUCKET, strtolower($file), $testDataDir . DIRECTORY_SEPARATOR . $file);
        }
    }

    public function proceedPipeline(): void
    {
        $pipeline = (new Pipeline())
            ->pipe(new JP2Stage)
            ->pipe(new DimensionsStage);

        foreach (HomePresenter::TEST_FILES as $file) {
            $pipeline->process($this->photoOfSpecimenFactory->create(HomePresenter::START_BUCKET, $file));
        }

    }
}
