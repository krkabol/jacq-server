<?php

declare(strict_types=1);

namespace app\Model\Stages;

use App\Model\Database\EntityManager;
use app\Services\S3Service;
use app\Services\TempDir;

class StageFactory
{

    private S3Service $s3Service;
    private TempDir $tempDir;
    private EntityManager $entityManager;


    public function __construct(S3Service $s3Service, TempDir $tempDir, EntityManager $entityManager)
    {
        $this->s3Service = $s3Service;
        $this->tempDir = $tempDir;
        $this->entityManager = $entityManager;
    }

    public function createNoveltyControlStage(): NoveltyControlStage
    {
        return new NoveltyControlStage( $this->s3Service);
    }

    public function createRegisterStage(): RegisterStage
    {
        return new RegisterStage($this->entityManager);
    }

}