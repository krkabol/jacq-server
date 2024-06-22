<?php

declare(strict_types=1);

namespace app\UI\Iiif;

use app\Services\S3Service;
use app\Services\StorageConfiguration;
use app\UI\Base\BasePresenter;
use Nette\Application\Responses\CallbackResponse;
use Nette\Http\Request;
use Nette\Http\Response;


final class IiifPresenter extends BasePresenter
{
    /** @inject */
    public S3Service $s3Service;
    /** @inject */
    public StorageConfiguration $configuration;

    public function actionImage($id)
    {
        $bucket = $this->configuration->getArchiveBucket();
        if ($this->s3Service->objectExists($bucket, $id)) {
            $head = $this->s3Service->headObject($bucket, $id);
            $stream = $this->s3Service->getStreamOfObject($bucket, $id);

            $callback = function (Request $httpRequest, Response $httpResponse) use ($id, $head, $stream){
                $httpResponse->addHeader("Content-Type", $head['ContentType']);
                $httpResponse->addHeader('Content-Disposition', "inline; filename" . $id);
                fpassthru($stream);
                fclose($stream);
            };

            $response = new CallbackResponse($callback);
            $this->sendResponse($response);
        }
        $this->error("The requested image does not exists.");
    }

}
