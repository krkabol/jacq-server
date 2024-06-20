<?php

namespace app\Model\Database\Entity;

use app\Model\Database\Entity\Attributes\TId;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'photo')]
class Photo
{

    use TId;

    #[ORM\Column(unique: true, nullable: false)]
    protected string $key;

}
