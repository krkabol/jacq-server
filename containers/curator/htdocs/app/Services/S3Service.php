<?php

declare(strict_types=1);

namespace app\Services;

use Aws\Exception\AwsException;
use Aws\Result;
use Aws\S3\S3Client;

class S3Service
{
    public S3Client $s3;

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

    public function putObject()
    {
        $insert = $this->s3->putObject(['Bucket' => 'prc', 'Key' => 'testkey', 'Body' => 'Hello from MinIO!!']);
    }

    public function getObject()
    {
        $retrive = $this->s3->getObject(['Bucket' => 'prc', 'Key' => 'testkey', 'SaveAs' => 'testkey_local']);
    }
}