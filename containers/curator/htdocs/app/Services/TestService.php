<?php

declare(strict_types=1);

namespace app\Services;

use app\Model\PhotoOfSpecimenFactory;
use app\Model\Stages\BarcodeStage;
use app\Model\Stages\BaseStageException;
use app\Model\Stages\StageFactory;
use app\UI\Home\CuratorPresenter;
use League\Pipeline\Pipeline;

class TestService
{
    const LIMIT = 100;
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
        foreach (CuratorPresenter::TEST_FILES as $file) {
            $this->S3Service->putTiffIfNotExists($this->storageConfiguration->getNewBucket(), strtolower($file), $testDataDir . DIRECTORY_SEPARATOR . $file);
        }
    }

    public function proceedNewImages(): array
    {
        $pipeline = $this->fileProcessingPipeline();
        $success = [];
        $error = [];
        foreach (CuratorPresenter::TEST_FILES as $file) {
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

    public function proceedExistingImages(): array
    {
        $pipeline = $this->migrationPipeline();
        $success = [];
        $error = [];
        $files = $this->S3Service->listObjects($this->storageConfiguration->getArchiveBucket());
        $i = 0;
        foreach ($files as $file) {
            try {
                $photo = $this->photoOfSpecimenFactory->create($this->storageConfiguration->getArchiveBucket(), $file);
                $pipeline->process($photo);
                $success[$file] = "OK";
                $i++;
            } catch (BaseStageException $e) {
                $error[$file] = $e->getMessage();
            }
            if ($i >= self::LIMIT) {
                break;
            }
        }
        return [$success, $error];
    }

    protected function migrationPipeline(): Pipeline
    {
        return (new Pipeline())
            ->pipe($this->stageFactory->createNotInDatabaseStage())
            ->pipe($this->stageFactory->createFilenameControlStage())
            ->pipe($this->stageFactory->createDimensionsStage())
            ->pipe(new BarcodeStage)
            ->pipe($this->stageFactory->createRegisterStage())
            ->pipe($this->stageFactory->createCleanupStage());
    }
}
