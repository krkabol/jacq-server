<?php

declare(strict_types=1);

namespace app\Model\Stages;

use app\Model\PhotoOfSpecimen;
use League\Pipeline\StageInterface;

class FilenameControlException extends BaseStageException
{

}

class FilenameControlStage implements StageInterface
{
    const NAME_TEMPLATE = '/^(?P<herbarium>[a-zA-Z]+)_(?P<specimenId>\d+)\.(?P<extension>tif)$/';
    const HERBARIA = ["prc"];
    private PhotoOfSpecimen $item;

    public function __invoke($payload)
    {
        $this->item = $payload;
        $this->splitName();
        $this->checkAcronymExists();
        $this->checkSpecimenExists();
        return $this->item;
    }

    private function splitName(): void
    {

        $parts = [];

        if (preg_match(self::NAME_TEMPLATE, $this->item->getObjectKey(), $parts)) {
            $this->item->setHerbariumAcronym($parts['herbarium']);
            $this->item->setSpecimenId($parts['specimenId']);
        } else {
            throw new FilenameControlException("invalid name format: " . $this->item->getObjectKey());
        }
    }

    private function checkAcronymExists(): void
    {
        if (!in_array(strtolower($this->item->getHerbariumAcronym()), self::HERBARIA)) {
            throw new FilenameControlException("invalid herbarium acronym: " . $this->item->getHerbariumAcronym());
        }
    }

    private function checkSpecimenExists(): void
    {
        // TODO - will we ask JACQ API? - because it is possible to have a specimen with photo not yet included in JACQ I expect..
    }

}
