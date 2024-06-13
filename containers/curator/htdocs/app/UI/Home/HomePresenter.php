<?php

declare(strict_types=1);

namespace app\UI\Home;

use Nette;


final class HomePresenter extends Nette\Application\UI\Presenter
{
    public function actionInitialize()
    {

        $this->redirect(":default");
    }

    public function actionProceed()
    {

        $this->redirect(":default");
    }
}
