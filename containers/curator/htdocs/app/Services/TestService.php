<?php

declare(strict_types=1);

namespace app\Services;

use app\Model\PhotoOfSpecimenFactory;
use app\Model\Stages\ArchiveStage;
use app\Model\Stages\BarcodeStage;
use app\Model\Stages\BaseStageException;
use app\Model\Stages\CleanupStage;
use app\Model\Stages\ConvertStage;
use app\Model\Stages\DimensionsStage;
use app\Model\Stages\FilenameControlStage;
use app\Model\Stages\StageFactory;
use app\UI\Home\HomePresenter;
use League\Pipeline\Pipeline;

class TestService
{
    private S3Service $S3Service;
    private WebDir $webDir;

    private PhotoOfSpecimenFactory $photoOfSpecimenFactory;
    private StageFactory $stageFactory;

    public function __construct(S3Service $S3Service, WebDir $webDir, PhotoOfSpecimenFactory $photoOfSpecimenFactory, StageFactory $stageFactory)
    {
        $this->S3Service = $S3Service;
        $this->webDir = $webDir;
        $this->photoOfSpecimenFactory = $photoOfSpecimenFactory;
        $this->stageFactory = $stageFactory;
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

    public function proceedNewImages(): array
    {
        $pipeline = $this->fileProcessingPipeline();
        $success = [];
        $error = [];
        foreach (HomePresenter::TEST_FILES as $file) {
            try {
                $photo = $this->photoOfSpecimenFactory->create(HomePresenter::START_BUCKET, $file);
                $pipeline->process($photo);
                $success[$file] = "OK";
            } catch (BaseStageException $e) {
                $error[$file] = $e->getMessage();
            }
        }
        return [$success, $error];

    }

    protected function fileProcessingPipeline(): Pipeline
    {
        return $this->controlPipeline()
            ->pipe(new ConvertStage)
            ->pipe(new ArchiveStage)
            ->pipe($this->stageFactory->createRegisterStage())
            ->pipe(new CleanupStage);
    }

    protected function controlPipeline(): Pipeline
    {
        return (new Pipeline())
            ->pipe(new FilenameControlStage)
            ->pipe($this->stageFactory->createNoveltyControlStage())
            ->pipe(new DimensionsStage)
            ->pipe(new BarcodeStage);
    }
}
