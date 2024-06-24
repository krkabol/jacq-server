<?php

declare(strict_types=1);

namespace app\Services;

use app\Model\PhotoOfSpecimenFactory;
use app\Model\Stages\BarcodeStage;
use app\Model\Stages\BaseStageException;
use app\Model\Stages\StageFactory;
use app\UI\Test\TestPresenter;
use League\Pipeline\Pipeline;

class TestService extends ImageService
{
    protected WebDir $webDir;

    public function __construct(S3Service $S3Service, WebDir $webDir, PhotoOfSpecimenFactory $photoOfSpecimenFactory, StageFactory $stageFactory, StorageConfiguration $storageConfiguration)
    {
        $this->webDir = $webDir;
        parent::__construct($S3Service, $photoOfSpecimenFactory, $stageFactory, $storageConfiguration);
    }

    public function initialize(): void
    {
        foreach ($this->storageConfiguration->getAllBuckets() as $bucket) {
            $this->S3Service->createBucket($bucket);
        }

        $testDataDir = $this->webDir->getPath('data');
        foreach (TestPresenter::TEST_FILES as $file) {
            $this->S3Service->putTiffIfNotExists($this->storageConfiguration->getNewBucket(), strtolower($file), $testDataDir . DIRECTORY_SEPARATOR . $file);
        }
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
