<?php

namespace app\Model\Database\Entity;

use app\Model\Database\Entity\Attributes\TId;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'herbaria')]
class Herbaria
{

    use TId;

    #[ORM\Column(unique: true, nullable: false)]
    protected string $acronym;

    #[ORM\OneToMany(targetEntity: "Photos", mappedBy: "herbarium")]
    protected $photos;
}
