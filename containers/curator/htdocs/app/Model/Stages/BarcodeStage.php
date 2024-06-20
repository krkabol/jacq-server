<?php

declare(strict_types=1);

namespace app\Model\Stages;

use app\Model\PhotoOfSpecimen;
use League\Pipeline\StageInterface;

class BarcodeStageException extends BaseStageException
{

}

class BarcodeStage implements StageInterface
{
    const BARCODE_TEMPLATE = '/^(?P<herbarium>[a-zA-Z]+)[ _-]+(?P<specimenId>\d+)$/';
    private PhotoOfSpecimen $item;

    public function __invoke($payload)
    {
        $this->item = $payload;
        $this->createContrastedImage();
        $this->validateFilename();
        unlink($this->getContrastTempFileName());
        return $this->item;
    }

    private function createContrastedImage(): void
    {
        $this->item->getImagick()->modulateImage(100, 0, 100);
        $this->item->getImagick()->whiteThresholdImage('#a9a9a9');
        $this->item->getImagick()->contrastImage(true);
        $this->item->getImagick()->setImageFormat('jpg');
        $this->item->getImagick()->writeImage($this->getContrastTempFileName());
    }

    private function getContrastTempFileName(): string
    {
        return $this->item->getTempfile() . "barcode";
    }

    private function validateFilename(): void
    {
        $isValid = false;
        $codes = $this->detectCodes();
        foreach ($codes as $code) {
            $parts = [];
            if (preg_match(self::BARCODE_TEMPLATE, $code, $parts)) {
                if ($this->item->getHerbariumAcronym() === strtolower($parts['herbarium']) &&
                    $this->item->getSpecimenId() === $parts['specimenId']) {
                    $isValid = true;
                }
            }
        }
        if (!$isValid) {
            throw new BarcodeStageException("wrong barcode or image name: " . $this->item->getObjectName() . ". Detected code(s): " . implode($codes));
        }
    }

    private function detectCodes(): array
    {
        $output = [];
        $returnVar = 0;
        exec("zbarimg --quiet --raw " . escapeshellarg($this->getContrastTempFileName()), $output, $returnVar);

        if ($returnVar !== 0) {
            throw new BarcodeStageException("zbar script error");
        }

        if (empty($output)) {
            throw new BarcodeStageException("empty output from zbar");
        }
        return $output;
    }

}
