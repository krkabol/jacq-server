<?php declare(strict_types=1);

namespace app\Services;

final class StorageConfiguration
{

    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getNewBucket(): string
    {
        return $this->config['newBucket'];
    }

    public function getArchiveBucket(): string
    {
        return $this->config['archiveBucket'];
    }

    public function getJP2Bucket(): string
    {
        return $this->config['jp2Bucket'];
    }

    public function getAllBuckets(): array
{
        return [$this->getNewBucket(), $this->getArchiveBucket(), $this->getJP2Bucket()];
   }

}
