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
    protected int $typeId;

    public function __construct(
        Collection $cparReports,
        string $branchName = 'ALL BRANCH',
        string $dateFrom = '',
        string $dateTo = '',
        int $typeId = 5
    ) {
        $this->cparReports = $cparReports;
        $this->branchName = $branchName;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->typeId = $typeId;
    }

    public function collection()
    {
        return $this->cparReports;
    }

    public function headings(): array
    {
        if ($this->typeId == 10) {
            return [
                'Request No.',
                'Date Reported',
                'Reported By',
                'Patient Name',
                'Attending Physician',
                'Source of Information',
                'Test Procedure',
                'Actual Released Date',
                'Complainant Category',
                'Complainant Name',
                'Data Information Error',
                'Technical Equipment Issues',
                'Quality Accuracy Issues',
                'Concern Description',
                'Reported Employee',
                'Department',
                'Identified Cause',
                'Provided Solution',
                'Date Completed',
                'Tat',
                'Decision Name',
                'Category Name',
                'Offense Level',
                'Subject',
                'Memo Date',
                'Memo Re',
                'Memo Content',
                'Status',
            ];
        }

        return [
            'Request No.',
            'Date Reported',
            'Reported By',
            'Source of Origin',
            'Complainant Category',
            'Complainant Name',
            'Concern Description',
            'Concern Category',
            'Reported Employee',
            'Department',
            'Identified Cause',
            'Provided Solution',
            'Date Completed',
            'Tat',
            'Decision Name',
            'Category Name',
            'Offense Level',
            'Subject',
            'Memo Date',
            'Memo Re',
            'Memo Content',
            'Status',
        ];
    }

    public function map($record): array
    {
        if ($this->typeId == 10) {
            return [
                $record->record_no ?? '—',

                !empty($record->date_open)
                    ? Carbon::parse($record->date_open)->format('m/d/Y')
                    : '—',
                $record->reported_by ?? '—',
                $record->patient_name ?? '—',
                $record->attending_physician ?? '—',
                $record->source_name ?? '—',
                $record->test_procedure ?? '—',
                $record->actual_released_date ?? '—',
                $record->complain_name ?? '—',
                $record->complainant_name ?? '—',
                $record->data_name ?? '—',
                $record->technical_name ?? '—',
                $record->quality_name ?? '—',
                $record->concern_description ?? '-',
                $record->employee_name ?? '—',
                $record->department_name ?? '—',
                $record->identified_cause ?? '—',
                $record->provided_solution ?? '—',
                $record->date_completed ?? '—',
                $record->tat ?? '—',
                $record->decision_name ?? '—',
                $record->category_name ?? '—',
                $record->offense_name ?? '—',
                $record->subject ?? '—',
                $record->memo_date ?? '—',
                $record->memo_re ?? '—',
                $record->memo_content ?? '—',
                $record->status_name ?? '—',
            ];
        }

        return [
            $record->record_no ?? '—',
            !empty($record->date_open) ? Carbon::parse($record->date_open)->format('m/d/Y') : '—',
            $record->reported_by ?? '—',
            $record->source_name ?? '—',
            $record->complain_name ?? '—',
            $record->complainant_name ?? '—',
            $record->concern_description ?? '—',
            $record->concern_name ?? '—',
            $record->employee_name ?? '—',
            $record->department_name ?? '—',
            $record->identified_cause ?? '—',
            $record->provided_solution ?? '—',
            $record->date_completed ?? '—',
            $record->tat ?? '—',
            $record->decision_name ?? '—',
            $record->category_name ?? '—',
            $record->offense_name ?? '—',
            $record->subject ?? '—',
            $record->memo_date ?? '—',
            $record->memo_re ?? '—',
            $record->memo_content ?? '—',
            $record->status_name ?? '—',

        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestColumn = $this->typeId == 10 ? 'W' : 'P';

        $sheet->getStyle("A1:{$highestColumn}1")
            ->getFont()
            ->setBold(true);

        $sheet->getStyle("A1:{$highestColumn}1")
            ->getAlignment()
            ->setHorizontal('center')
            ->setVertical('center');

        $sheet->getStyle(
            "A1:{$highestColumn}" . ($this->cparReports->count() + 1)
        )
            ->getAlignment()
            ->setVertical('center');

        $sheet->freezePane('A2');

        return [];
    }
}
