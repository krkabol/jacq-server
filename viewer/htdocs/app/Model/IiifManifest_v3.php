<?php

declare(strict_types=1);

namespace App\Model;
class IiifManifest_v3
{
    protected  $default;
    public function __construct()
    {
        $filePath = '../app/Model/x.json';

        $this->default = json_decode(file_get_contents($filePath), true);
    }

    public function getDefault()
    {
        return $this->default;
    }

}
