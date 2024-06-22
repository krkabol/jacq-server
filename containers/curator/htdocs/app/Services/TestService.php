<?php

declare(strict_types=1);

namespace app\Services;

use app\Model\PhotoOfSpecimenFactory;
use app\Model\Stages\BarcodeStage;
use app\Model\Stages\BaseStageException;
use app\Model\Stages\FilenameControlStage;
use app\Model\Stages\StageFactory;
use app\UI\Home\HomePresenter;
use League\Pipeline\Pipeline;

class TestService
{
    protected S3Service $S3Service;
    protected WebDir $webDir;

    protected PhotoOfSpecimenFactory $photoOfSpecimenFactory;
    protected StageFactory $stageFactory;

    protected StorageConfiguration $storageConfiguration;

    public function __construct(S3Service $S3Service, WebDir $webDir, PhotoOfSpecimenFactory $photoOfSpecimenFactory, StageFactory $stageFactory, StorageConfiguration $storageConfiguration)
    {
        $this->S3Service = $S3Service;
        $this->webDir = $webDir;
        $this->photoOfSpecimenFactory = $photoOfSpecimenFactory;
        $this->stageFactory = $stageFactory;
        $this->storageConfiguration = $storageConfiguration;
    }

    public function initialize(): void
    {
        foreach ($this->storageConfiguration->getAllBuckets() as $bucket) {
            $this->S3Service->createBucket($bucket);
        }

        $testDataDir = $this->webDir->getPath('data');
        foreach (HomePresenter::TEST_FILES as $file) {
            $this->S3Service->putTiffIfNotExists($this->storageConfiguration->getNewBucket(), strtolower($file), $testDataDir . DIRECTORY_SEPARATOR . $file);
        }
    }

    public function proceedNewImages(): array
    {
        $pipeline = $this->fileProcessingPipeline();
        $success = [];
        $error = [];
        foreach (HomePresenter::TEST_FILES as $file) {
            try {
                $photo = $this->photoOfSpecimenFactory->create($this->storageConfiguration->getNewBucket(), $file);
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
            ->pipe($this->stageFactory->createConvertStage())
            ->pipe($this->stageFactory->createArchiveStage())
            ->pipe($this->stageFactory->createRegisterStage())
            ->pipe($this->stageFactory->createCleanupStage());
    }

    protected function controlPipeline(): Pipeline
    {
        return (new Pipeline())
            ->pipe($this->stageFactory->createFilenameControlStage())
            ->pipe($this->stageFactory->createNoveltyControlStage())
            ->pipe($this->stageFactory->createDimensionsStage())
            ->pipe(new BarcodeStage);
    }
}
