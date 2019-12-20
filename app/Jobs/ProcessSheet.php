<?php

namespace App\Jobs;

use App\Models\Sheet;
use App\Imports\SheetImport;

use Maatwebsite\Excel\Facades\Excel;

class ProcessSheet extends Job
{
    private $sheet;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Sheet $sheet)
    {
        $this->sheet = $sheet;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $path = storage_path("app/{$this->sheet->file}");

            Excel::import(new SheetImport(), $path);

            $this->sheet->status = Sheet::FULFILLED;
        } catch (\Throwable $error) {
            $this->sheet->status = Sheet::REJECTED;

            throw $error;
        } finally {
            $this->sheet->save();
        }
    }
}
