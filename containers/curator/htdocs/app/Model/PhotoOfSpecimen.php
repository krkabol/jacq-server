<?php

declare(strict_types=1);

namespace app\Model;

class PhotoOfSpecimen
{

    private string $sourceBucket;
    private string $objectName;

    public function __construct(string $bucket, string $objectName)
    {
        $this->sourceBucket = $bucket;
        $this->objectName = $objectName;
    }


}
