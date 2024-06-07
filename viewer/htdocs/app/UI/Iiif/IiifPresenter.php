<?php

declare(strict_types=1);

namespace App\UI\IIIF;

use App\Model\IiifManifest_v3;
use Nette;


final class IiifPresenter extends Nette\Application\UI\Presenter
{

    public function actionManifest($id)
    {
        $model = (new IiifManifest_v3())->getDefault();
        $model["id"] = 'http://localhost:7080/iiif/manifest/'.$id;
        $this->sendJson($model);
    }
}
