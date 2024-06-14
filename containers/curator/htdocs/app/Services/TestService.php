<?php

declare(strict_types=1);

namespace app\Services;

use app\Model\JP2Stage;
use app\Model\PhotoOfSpecimen;
use app\UI\Home\HomePresenter;
use League\Pipeline\Pipeline;

class TestService
{
    private S3Service $S3Service;
    private WebDir $webDir;

    public function __construct(S3Service $S3Service, WebDir $webDir)
    {
         $this->S3Service = $S3Service;
         $this->webDir = $webDir;
    }

    public function initialize()
    {
        foreach (HomePresenter::BUCKETS as $bucket) {
            $this->S3Service->createBucket($bucket);
        }

        $testDataDir = $this->webDir->getPath('data');
        foreach (HomePresenter::TEST_FILES as $file) {
            $this->S3Service->putTiffIfNotExists(HomePresenter::START_BUCKET, strtolower($file), $testDataDir . DIRECTORY_SEPARATOR . $file);
        }
    }

    public function proceedPipeline()
    {
        $pipeline = (new Pipeline())
            ->pipe(new JP2Stage);

        foreach (HomePresenter::TEST_FILES as $file) {
            $pipeline->process(new PhotoOfSpecimen(HomePresenter::START_BUCKET, $file));
        }

    }
}
