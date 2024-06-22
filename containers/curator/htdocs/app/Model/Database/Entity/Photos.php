<?php

namespace app\Model\Database\Entity;

use app\Model\Database\Entity\Attributes\TCreatedAt;
use app\Model\Database\Entity\Attributes\TId;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'photos', options:["comment"=>"Specimen photos"])]
class Photos
{

    use TId;
    use TCreatedAt;

    #[ORM\Column(unique: true, nullable: false, options:["comment"=>"Filename of Archive Master TIF file"])]
    protected string $archiveFilename;

    #[ORM\ManyToOne(targetEntity: "Herbaria", inversedBy: "photos")]
    #[ORM\JoinColumn(name: "herbarium_id", referencedColumnName: "id", options:["comment"=>"Herbarium storing and managing the specimen data"])]
    protected Herbaria $herbarium;

    #[ORM\Column(type: Types::STRING, nullable: true, options:["comment"=>"Herbarium internal unique id of specimen in form without herbarium acronym"])]
    protected ?string $specimenId;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options:["comment"=>"Width of image with pixels"])]
    protected ?int $width;
    #[ORM\Column(type: Types::INTEGER, nullable: true, options:["comment"=>"Height of image in pixels"])]
    protected ?int $height;

    #[ORM\Column(type: Types::BIGINT, nullable: true, options:["comment"=>"Filesize of Archive Master TIFF file in bytes"])]
    protected ?int $archiveFileSize;

    #[ORM\Column(type: Types::BIGINT, nullable: true, options:["comment"=>"Filesize of converted JP2 file in bytes"])]
    protected ?int $JP2FileSize;
    #[ORM\Column(type: Types::BOOLEAN, nullable: false, options:["comment"=>"Flag with not finally usage decided yet"])]
    protected bool $finalized = false;

    public function setArchiveFilename(string $archiveFilename): Photos
    {
        $this->archiveFilename = $archiveFilename;
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

    public function setArchiveFileSize(?int $archiveFileSize): Photos
    {
        $this->archiveFileSize = $archiveFileSize;
        return $this;
    }

    public function setJP2FileSize(?int $JP2FileSize): Photos
    {
        $this->JP2FileSize = $JP2FileSize;
        return $this;
    }

    public function getArchiveFilename(): string
    {
        return $this->archiveFilename;
    }

    public function getHerbarium(): Herbaria
    {
        return $this->herbarium;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function getSpecimenId(): ?string
    {
        return $this->specimenId;
    }

    public function getArchiveFileSize(): ?int
    {
        return $this->archiveFileSize;
    }

    public function getJP2FileSize(): ?int
    {
        return $this->JP2FileSize;
    }

    public function isFinalized(): bool
    {
        return $this->finalized;
    }

}
