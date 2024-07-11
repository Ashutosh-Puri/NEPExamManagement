<?php

namespace App\Exports\User\ExamFormStatistics;

use App\Models\Exam;
use App\Models\Exampatternclass;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExamFormStatisticsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithCustomStartCell, WithEvents
{
    protected $totals;

    public function __construct()
    {
        $this->totals = [
            'total_students' => 0,
            'incomplete_forms' => 0,
            'yet_to_inward_forms' => 0,
            'inward_completed_forms' => 0,
            'total_fee_received' => 0,
        ];
    }

    public function collection()
    {   
        $exam = Exam::where('status', 1)->first();

        $exampatternclasses = Exampatternclass::with(['patternclass.courseclass.classyear:id,classyear_name','patternclass.courseclass.course:id,course_name', 'patternclass.pattern:id,pattern_name'])
            ->selectRaw('*, getTotalStudents(patternclass_id,' . $exam->academicyear_id . ') AS total_students ,getIncompleteForms(patternclass_id, exam_id) AS incomplete_forms, getYetToInwardForms(patternclass_id, exam_id) AS yet_to_inward_forms ,getInwardCompletedForms(patternclass_id, exam_id) AS inward_completed_forms , getTotalFeeReceived(patternclass_id, exam_id) AS total_fee_received ')
            ->where('exam_id', $exam->id)
            ->get();

        foreach ($exampatternclasses as $exampatternclass) {
            $this->totals['total_students'] += $exampatternclass->total_students;
            $this->totals['incomplete_forms'] += $exampatternclass->incomplete_forms;
            $this->totals['yet_to_inward_forms'] += $exampatternclass->yet_to_inward_forms;
            $this->totals['inward_completed_forms'] += $exampatternclass->inward_completed_forms;
            $this->totals['total_fee_received'] += $exampatternclass->total_fee_received;
        }

        return $exampatternclasses;
    }

    public function headings(): array
    {
        return [
            'ID',
            'PATTERN CLASS ',
            'TOTAL STUDENTS ',
            'INCOMPLETE ',
            'YET TO INWARD',
            'INWARD COMPLETED ',
            'TOTAL FEE RECEIVED ',
        ];
    }

    public function map($row): array
    {   
        return [
            $row->id,
            (isset($row->patternclass->courseclass->classyear->classyear_name) ? $row->patternclass->courseclass->classyear->classyear_name : '-').''.(isset($row->patternclass->courseclass->course->course_name) ? $row->patternclass->courseclass->course->course_name : '-').' '.(isset($row->patternclass->pattern->pattern_name) ? $row->patternclass->pattern->pattern_name : '-'),
            (string) $row->total_students,
            (string) $row->incomplete_forms,
            (string) $row->yet_to_inward_forms,
            (string) $row->inward_completed_forms,
            !empty($row->total_fee_received)?(string) $row->total_fee_received:"0",
        ];
    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $lastRow = $event->sheet->getHighestRow() + 1;

                $event->sheet->appendRows([
                    ['TOTAL', '', (string) $this->totals['total_students'], (string) $this->totals['incomplete_forms'], (string) $this->totals['yet_to_inward_forms'], (string) $this->totals['inward_completed_forms'], (string) $this->totals['total_fee_received']]
                ], $event);

                $event->sheet->mergeCells('A' . $lastRow . ':B' . $lastRow);

                $event->sheet->getStyle('A' . $lastRow . ':G' . $lastRow)->getFont()->setBold(true);

                $event->sheet->getStyle('A1:G' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                $event->sheet->getStyle('C2:G' . $lastRow)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_RIGHT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'indent' => 1,
                    ],
                ]);

                $event->sheet->getStyle('A2:A' . $lastRow)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'indent' => 1,
                    ],
                ]);
            },
        ];
    }
}