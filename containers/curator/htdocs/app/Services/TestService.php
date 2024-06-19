<?php

declare(strict_types=1);

namespace app\Services;

use app\Model\PhotoOfSpecimenFactory;
use app\Model\Stages\BarcodeStage;
use app\Model\Stages\ConvertStage;
use app\Model\Stages\DimensionsStage;
use app\Model\Stages\FilenameControlStage;
use app\Model\Stages\FinalStage;
use app\Model\Stages\InitialStage;
use app\Model\Stages\RegisterStage;
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

    protected function dryRunPipeline() : Pipeline {
        return (new Pipeline())
        ->pipe(new FilenameControlStage)
        ->pipe(new InitialStage)
        ->pipe(new ConvertStage)
        ->pipe(new DimensionsStage)
        ->pipe(new BarcodeStage);
    }

    protected function fullRunPipeline() : Pipeline {
        return $this->dryRunPipeline()
        ->pipe(new RegisterStage)
        ->pipe(new FinalStage);
    }

    public function proceedNewImages(): void
    {
        $pipeline = $this->dryRunPipeline();

        foreach (HomePresenter::TEST_FILES as $file) {
            $pipeline->process($this->photoOfSpecimenFactory->create(HomePresenter::START_BUCKET, $file));
        }

    }
}
