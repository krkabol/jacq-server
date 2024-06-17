<?php

declare(strict_types=1);

namespace app\Services;

use Aws\Exception\AwsException;
use Aws\Result;
use Aws\S3\S3Client;

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
        try {
            $result = $this->s3->headBucket(['Bucket' => $name,]);
        } catch (AwsException $e) {
            if ($e->getStatusCode() == 404) {
                try {
                    $result = $this->s3->createBucket(['Bucket' => $name,]);
                } catch (AwsException $e) {
                    die("Error during bucket create: " . $e->getMessage() . "\n");
                }
            } else {
                die("Error during bucket head: " . $e->getMessage() . "\n");
            }
        }
    }

    public function listBuckets(): Result
    {
        return $this->s3->listBuckets();
    }

    public function putTiffIfNotExists(string $bucket, string $key, string $path): void
    {
        try {
            $this->s3->headObject([
                'Bucket' => $bucket,
                'Key' => $key,
            ]);
        } catch (AwsException $e) {
            if ($e->getStatusCode() === 404) {
                $result = $this->s3->putObject([
                    'Bucket' => $bucket,
                    'Key' => $key,
                    'SourceFile' => $path,
                    'ContentType' => 'image/tiff']);
            }
        }
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
