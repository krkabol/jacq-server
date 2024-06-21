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
    protected string $key;

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
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $msg;

}
