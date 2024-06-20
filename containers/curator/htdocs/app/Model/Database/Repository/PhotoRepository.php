<?php declare(strict_types = 1);

namespace app\Model\Database\Repository;

use app\Model\Database\Entity\Photo;

/**
 * @method Photo|NULL find($id, ?int $lockMode = NULL, ?int $lockVersion = NULL)
 * @method Photo|NULL findOneBy(array $criteria, array $orderBy = NULL)
 * @method Photo[] findAll()
 * @method Photo[] findBy(array $criteria, array $orderBy = NULL, ?int $limit = NULL, ?int $offset = NULL)
 * @extends AbstractRepository<\app\Model\Database\Entity\Photo>
 */
class PhotoRepository extends AbstractRepository
{

	public function findOneByKey(string $key): ?Photo
	{
		return $this->findOneBy(['key' => $key]);
	}

}
