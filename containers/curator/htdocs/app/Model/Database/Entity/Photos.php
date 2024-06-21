<?php

namespace app\Model\Database\Entity;

use app\Model\Database\Entity\Attributes\TId;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'photos')]
class Photos
{

    use TId;

    #[ORM\Column(unique: true, nullable: false)]
    protected string $filename;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    protected ?int $width;
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    protected ?int $height;
    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $specimenId;

    #[ORM\ManyToOne(targetEntity: "Herbaria", inversedBy: "photos")]
    #[ORM\JoinColumn(name: "herbarium_id", referencedColumnName: "id")]
    protected Herbaria $herbarium;
    #[ORM\Column(type: Types::BOOLEAN, nullable: false)]
    protected bool $finalized = false;

    public function setFilename(string $filename): Photos
    {
        $this->filename = $filename;
        return $this;
    }

    public function setWidth(?int $width): Photos
    {
        $this->width = $width;
        return $this;
    }

    public function setHeight(?int $height): Photos
    {
        $this->height = $height;
        return $this;
    }

    public function setSpecimenId(?string $specimenId): Photos
    {
        $this->specimenId = $specimenId;
        return $this;
    }

    public function setHerbarium(Herbaria $herbarium): Photos
    {
        $this->herbarium = $herbarium;
        return $this;
    }

    public function setFinalized(bool $finalized): Photos
    {
        $this->finalized = $finalized;
        return $this;
    }


}
