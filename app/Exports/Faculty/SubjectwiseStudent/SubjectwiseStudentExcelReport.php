<?php

namespace App\Exports\Faculty\SubjectwiseStudent;

use App\Models\Subject;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SubjectwiseStudentExcelReport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    protected $subject_with_students;
    private $counter = 1;

    public function __construct($subject_with_students)
    {
        $this->subject_with_students = $subject_with_students;
    }

    public function collection()
    {
        return $this->subject_with_students->studentExamforms;
    }

    public function headings(): array
    {
        return [
            [date('d-M-Y h:i A', strtotime('now')), '', '', '', '', '', '', ''],
            ['Sangamner Nagarpalika Arts, D.J. Malpani Commerce and B.N. Sarada Science College (Autonomous) Sangamner', '', '', '', '', '', '', ''],
            ['(Affiliated to Savitribai Phule Pune University)', '', '', '', '', '', '', ''],
            [$this->subject_with_students->studentExamforms->first()->exam->exam_name, '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['Sem: '.$this->subject_with_students->subject_sem, '', 'Subject Code & Name: '.$this->subject_with_students->subject_code.' '.$this->subject_with_students->subject_name, '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
            ['Sr.No', 'PRN / Seat No.', 'Name of the Student', 'Mobile', 'Internal', 'External', 'Admission Year', 'Class'],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->getRowDimension(1)->setRowHeight(15);
                $event->sheet->getRowDimension(2)->setRowHeight(15);
                $event->sheet->getRowDimension(3)->setRowHeight(15);
                $event->sheet->getRowDimension(4)->setRowHeight(15);
                $event->sheet->getRowDimension(5)->setRowHeight(15);
                $event->sheet->getRowDimension(6)->setRowHeight(15);
                $event->sheet->getRowDimension(7)->setRowHeight(15);

                $event->sheet->mergeCells('A1:H1');

                $event->sheet->mergeCells('A2:H2');

                $event->sheet->mergeCells('A3:H3');

                $event->sheet->mergeCells('A4:H4');

                $event->sheet->mergeCells('A5:H5');

                $event->sheet->mergeCells('A6:B6');

                $event->sheet->mergeCells('C6:E6');

                $event->sheet->getStyle('A1:H8')->getFont();

                $event->sheet->getStyle('A6:H6')->applyFromArray(['font' => ['bold' => true]]);

                $event->sheet->getStyle('A1:H10000')->getAlignment()->setHorizontal('center');
            },
        ];
    }

    public function map($row): array
    {
        $counterValue = $this->counter++;

        return [
            $counterValue,
            $row->student->prn,
            $row->student->student_name,
            $row->student->mobile_no,
            $row->int_status == 1 ? 'YES' : 'NO',
            $row->ext_status == 1 ? 'YES' : 'NO',
            $row->exam->academicyear->year_name,
            get_pattern_class_name($row->subject->patternclass_id),
        ];
    }

}
