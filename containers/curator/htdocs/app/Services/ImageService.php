<?php

declare(strict_types=1);

namespace app\Services;

use app\Model\PhotoOfSpecimenFactory;
use app\Model\Stages\BarcodeStage;
use app\Model\Stages\BaseStageException;
use app\Model\Stages\StageFactory;
use app\UI\Test\TestPresenter;
use League\Pipeline\Pipeline;

class ImageService
{
    const LIMIT = 100;
    protected S3Service $S3Service;
    protected PhotoOfSpecimenFactory $photoOfSpecimenFactory;
    protected StageFactory $stageFactory;
    protected StorageConfiguration $storageConfiguration;

    public function __construct(S3Service $S3Service, PhotoOfSpecimenFactory $photoOfSpecimenFactory, StageFactory $stageFactory, StorageConfiguration $storageConfiguration)
    {
        $this->S3Service = $S3Service;
        $this->photoOfSpecimenFactory = $photoOfSpecimenFactory;
        $this->stageFactory = $stageFactory;
        $this->storageConfiguration = $storageConfiguration;
    }


    public function proceedNewImages(): array
    {
        $pipeline = $this->fileProcessingPipeline();
        $success = [];
        $error = [];
        foreach (TestPresenter::TEST_FILES as $file) {
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
