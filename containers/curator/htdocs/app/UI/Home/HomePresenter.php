<?php

declare(strict_types=1);

namespace app\UI\Home;

use app\Services\S3Service;
use Nette;


final class HomePresenter extends Nette\Application\UI\Presenter
{

    public const BUCKETS = ["archive", "iiif", "new"];
    /**
     * @inject
     */
    public S3Service $service;

    public function renderDefault()
    {
        $this->service->bucketsExists(self::BUCKETS)?$this->template->bucketsOK = true:$this->template->bucketsOK = false;
        $this->template->tiffOK = false;

        $this->template->buckets = $this->service->listBuckets();
    }

    public function actionInitialize()
    {
        foreach ($this::BUCKETS as $bucket) {
            $this->service->createBucket($bucket);
        }
        $this->redirect(":default");
    }

    public function actionProceed()
    {

        $this->redirect(":default");
    }
}
