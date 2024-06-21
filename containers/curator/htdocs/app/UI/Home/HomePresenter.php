<?php

declare(strict_types=1);

namespace app\UI\Home;

use app\Services\S3Service;
use app\Services\TestService;
use Nette;


final class HomePresenter extends Nette\Application\UI\Presenter
{
    public const BUCKETS = ["archive", "iiif", "new"];
    public const START_BUCKET = "new";
    public const JP2_BUCKET = 'iiif';
    public const ARCHIVE_BUCKET = 'archive';
    public const TEST_FILES = ["prc_407087.tif", "prc_407135.tif"];

    /** @inject */
    public S3Service $s3Service;

    /** @inject */
    public TestService $testService;

    public function renderDefault()
    {
        $this->s3Service->bucketsExists(self::BUCKETS) ? $this->template->bucketsOK = true : $this->template->bucketsOK = false;
        $this->s3Service->objectsExists(self::START_BUCKET, self::TEST_FILES) ? $this->template->tiffOK = true : $this->template->tiffOK = false;

        $this->template->buckets = $this->s3Service->listBuckets();
    }

    public function actionInitialize()
    {
        $this->testService->initialize();
        $this->redirect(":default");
    }

    public function renderProceed()
    {
        $result = $this->testService->proceedNewImages();
        $this->template->success = $result[0];
        $this->template->error = $result[1];
    }
}
