<?php declare(strict_types=1);

namespace app\Model\Database;
use app\Model\Database\Entity\Photo;
use App\Model\UserRepository;

/**
 * @mixin EntityManager
 */
trait TRepositories
{

    public function getUserRepository(): UserRepository
    {
        return $this->getRepository(Photo::class);
    }

}
