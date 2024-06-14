<?php

declare(strict_types=1);

namespace app\UI\Home;

use app\Services\S3Service;
use app\Services\WebDir;
use Nette;


final class HomePresenter extends Nette\Application\UI\Presenter
{
    public const BUCKETS = ["archive", "iiif", "new"];
    public const TEST_FILES = ["prc_407087.tif","prc_407135.tif"];

    /** @inject  */
    public S3Service $service;

    /** @inject  */
    public WebDir $webDir;

    public function renderDefault()
    {
        $this->service->bucketsExists(self::BUCKETS)?$this->template->bucketsOK = true:$this->template->bucketsOK = false;
        $this->service->objectsExists("new", self::TEST_FILES)?$this->template->tiffOK = true:$this->template->tiffOK = false;

        $this->template->buckets = $this->service->listBuckets();
    }

    public function actionInitialize()
    {
        foreach ($this::BUCKETS as $bucket) {
            $this->service->createBucket($bucket);
        }

        $testDataDir = $this->webDir->getPath('data');
        foreach (self::TEST_FILES as $file) {
            $this->service->putTiffIfNotExists('new', strtolower($file), $testDataDir . DIRECTORY_SEPARATOR . $file);
        }

        $this->redirect(":default");
    }

    public function actionProceed()
    {

        $this->redirect(":default");
    }
}
