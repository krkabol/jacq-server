<?php

declare(strict_types=1);

namespace app\Model\Stages;

use app\Model\Database\Entity\Photos;
use App\Model\Database\EntityManager;
use app\Model\PhotoOfSpecimen;
use League\Pipeline\StageInterface;

class RegisterStageException extends BaseStageException
{

}

class RegisterStage implements StageInterface
{

    protected EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke($payload)
    {
        try {
            $this->writeRecord($payload);;
        } catch (\Exception $exception) {
            throw new RegisterStageException("db write error (" . $exception->getMessage() . "): " . $payload->getObjectKey());
        }
        return $payload;
    }

    protected function writeRecord($payload)
    {
        /** @var PhotoOfSpecimen $payload */
        $entity = new Photos();
        $herbarium = $this->entityManager->getHerbariaRepository()->findOneByAcronym($payload->getHerbariumAcronym());
        $entity
            ->setCreatedAt()
            ->setArchiveFilename($payload->getObjectKey())
            ->setFinalized(true)
            ->setHerbarium($herbarium)
            ->setHeight($payload->getHeight())
            ->setWidth($payload->getWidth())
            ->setArchiveFileSize($payload->getTiffSize())
            ->setJP2FileSize($payload->getJp2Size())
            ->setSpecimenId($payload->getSpecimenId());

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }


}
