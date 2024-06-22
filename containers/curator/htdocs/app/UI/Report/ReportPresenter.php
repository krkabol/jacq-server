<?php

declare(strict_types=1);

namespace app\UI\Report;

use App\Services\ReportService;
use app\UI\Base\BasePresenter;


final class ReportPresenter extends BasePresenter
{
    /** @inject  */
    public ReportService $reportService;

    public function renderIntegrity()
    {
        $this->template->dbRecordsMissingWithinArchive = $this->reportService->dbRecordsMissingWithinArchive();
        $this->template->dbRecordsMissingWithinIIIF = $this->reportService->dbRecordsMissingWithinIIIF();
        $this->template->unprocessedNewFiles = $this->reportService->unprocessedNewFiles();
        $this->template->TIFFsWithoutJP2 = $this->reportService->TIFFsWithoutJP2();
        $this->template->JP2sWithoutTIFF = $this->reportService->JP2sWithoutTIFF();
        $this->template->TIFFsWithoutDbRecord = $this->reportService->TIFFsWithoutDbRecord();

    }
}
