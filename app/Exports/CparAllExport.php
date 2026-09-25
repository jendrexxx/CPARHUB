<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CparAllExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithStyles
{
    protected Collection $cparReports;
    protected string $branchName;
    protected string $dateFrom;
    protected string $dateTo;

    public function __construct(
        Collection $cparReports,
        string $branchName = 'ALL BRANCH',
        string $dateFrom = '',
        string $dateTo = ''
    ) {
        $this->cparReports = $cparReports;
        $this->branchName = $branchName;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function collection()
    {
        return $this->cparReports;
    }

    public function headings(): array
    {
        return [
            'Request No.',
            'Date Reported',
            'Reported Employee',
            'Department',
            'Identified Cause',
            'Provided Solution',
            'Date Completed',
            'Tat',
            'Decision Name',
            'Category Name',
            'Offense Level',
            'Status',
        ];
    }

    public function map($record): array
    {
        return [
            $record->record_no ?? '—',

            !empty($record->date_open)
                ? Carbon::parse($record->date_open)->format('m/d/Y')
                : '—',

            $record->employee_name ?? '—',

            $record->department_name ?? '—',

            $record->identified_cause ?? '—',

            $record->provided_solution ?? '—',

            $record->date_completed ?? '—',

            $record->tat ?? '—',

            $record->decision_name ?? '—',

            $record->category_name ?? '—',

            $record->offense_name ?? '—',

            $record->status_name ?? '—',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:L1')->getFont()->setBold(true);

        $sheet->getStyle('A1:L1')
            ->getAlignment()
            ->setHorizontal('center')
            ->setVertical('center');

        $sheet->getStyle('A1:L' . ($this->cparReports->count() + 1))
            ->getAlignment()
            ->setVertical('center');

        $sheet->freezePane('A2');

        return [];
    }
}
