<?php

declare(strict_types=1);

namespace app\Model\Stages;


use App\Model\Database\EntityManager;
use app\Model\PhotoOfSpecimen;
use League\Pipeline\StageInterface;

class RegisterStage implements StageInterface
{

    private PhotoOfSpecimen $item;
    protected EntityManager $entityManager;
    protected $repository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getHerbariaRepository();
    }

    public function __invoke($payload)
    {
        return $payload;
    }


}
