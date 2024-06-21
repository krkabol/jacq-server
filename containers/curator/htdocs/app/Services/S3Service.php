<?php

declare(strict_types=1);

namespace app\Services;

use Aws\Exception\AwsException;
use Aws\Result;
use Aws\S3\S3Client;
use Nette\Neon\Exception;

class S3Service
{
    private S3Client $s3;

    public function __construct(S3Client $s3)
    {
        $this->s3 = $s3;
    }

    public function bucketsExists(array $buckets): bool
    {
        foreach ($buckets as $bucket) {
            if (!$this->s3->doesBucketExist($bucket)) {
                return false;
            }
        }
        return true;
    }

    public function objectExists(string $bucket, string $object): bool
    {
        if (!$this->s3->doesObjectExist($bucket, $object)) {
            return false;
        }
        return true;
    }

    public function objectsExists(string $bucket, array $objects): bool
    {
        foreach ($objects as $object) {
            if (!$this->s3->doesObjectExist($bucket, $object)) {
                return false;
            }
        }
        return true;
    }

    public function createBucket(string $name): void
    {
        if (!$this->s3->doesBucketExist($name)) {
            try {
                $result = $this->s3->createBucket(['Bucket' => $name,]);
            } catch (AwsException $e) {
                die("Error during bucket create: " . $e->getMessage() . "\n");
            }
        } else {
            throw new Exception("Bucket already exists");
        }
    }

    public function listBuckets(): Result
    {
        return $this->s3->listBuckets();
    }

    public function putTiffIfNotExists(string $bucket, string $key, string $path): bool
    {
        if (!$this->s3->doesObjectExist($bucket, $key)) {
            $result = $this->s3->putObject([
                'Bucket' => $bucket,
                'Key' => $key,
                'SourceFile' => $path,
                'ContentType' => 'image/tiff']);
            return true;
        }
        return false;
    }

    public function moveObjectIfNotExists(string $objectKey, string $sourceBucket, string $targetBucket): bool
    {
        if (!$this->s3->doesObjectExist($targetBucket, $objectKey)) {
            $this->s3->copyObject([
                'Bucket' => $targetBucket,
                'Key' => $objectKey,
                'CopySource' => "{$sourceBucket}/{$objectKey}",
            ]);

            $this->s3->deleteObject([
                'Bucket' => $sourceBucket,
                'Key' => $objectKey,
            ]);
            return true;
        }
        return false;
    }

    public function putJP2Overwrite(string $bucket, string $key, string $path): void
    {
        $result = $this->s3->putObject([
            'Bucket' => $bucket,
            'Key' => $key,
            'SourceFile' => $path,
            'ContentType' => 'image/jp2']);

    }

    public
    function getObject(string $bucket, string $key, string $path): Result
    {
        return $this->s3->getObject([
            'Bucket' => $bucket,
            'Key' => $key,
            'SaveAs' => $path]);
    }
}
