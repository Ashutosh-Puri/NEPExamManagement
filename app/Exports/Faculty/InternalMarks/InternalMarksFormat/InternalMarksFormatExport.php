<?php

namespace App\Exports\Faculty\InternalMarks\InternalMarksFormat;

use App\Models\Internalmarksbatch;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Models\Intbatchseatnoallocation;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InternalMarksFormatExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    protected $batch;
    protected $batch_id;
    protected $subject;
    protected $row_count=0;
    protected $faculty_name;
    protected $patternclass;
    public $counter = 1;

    public function __construct($batch_id)
    {
        $this->batch = Internalmarksbatch::select('id','exam_patternclasses_id','subject_id','faculty_id','created_at')
        ->where('id',$batch_id)
        ->where('faculty_id',Auth::guard('faculty')->user()->id)
        ->whereNotNull('faculty_id')
        ->with([
            'faculty:id,faculty_name,mobile_no',
            'subject:id,subject_name,subject_code',
            'exam_patternclass:id,patternclass_id',
            ])
        ->first();

        $this->subject = $this->batch->subject->subject_code.' '.$this->batch->subject->subject_name;
        $this->faculty_name = $this->batch->faculty->faculty_name.'('.$this->batch->faculty->mobile_no.')';
        $this->batch_id = $this->batch->created_at->format('Y') . $this->batch->subject_id . str_pad($this->batch->id, 5, '0', STR_PAD_LEFT);
        $this->patternclass = get_pattern_class_name($this->batch->exam_patternclass->patternclass_id);
    }

    public function collection()
    {
        $int_batch = Internalmarksbatch::select('id','exam_patternclasses_id','subject_id','subject_type','created_at')
        ->where('id', $this->batch->id)
        ->where('faculty_id', Auth::guard('faculty')->user()->id)
        ->whereNotNull('faculty_id')
        ->first();

        $int_batch_seatno = Intbatchseatnoallocation::select('id','student_id','intbatch_id','seatno')
        ->where('intbatch_id',$int_batch->id)
        ->with('student')
        ->orderBy('seatno', 'asc')
        ->get();

        $this->row_count = $int_batch_seatno->count();

        return $int_batch_seatno;
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                // Height of row
                $event->sheet->getRowDimension(1)->setRowHeight(15);
                $event->sheet->getRowDimension(2)->setRowHeight(15);
                $event->sheet->getRowDimension(3)->setRowHeight(15);
                $event->sheet->getRowDimension(4)->setRowHeight(15);
                $event->sheet->getRowDimension(5)->setRowHeight(15);
                $event->sheet->getRowDimension(6)->setRowHeight(15);
                $event->sheet->getRowDimension(7)->setRowHeight(15);
                $event->sheet->getRowDimension(8)->setRowHeight(15);


                $event->sheet->getStyle('A1:D1')->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                $event->sheet->getStyle('A2:D4')->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                $dataRange = 'A5:D' . $this->row_count+8;

                $event->sheet->getStyle($dataRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                $event->sheet->mergeCells('A1:D1');

                $event->sheet->mergeCells('A2:D2');

                $event->sheet->mergeCells('A3:D3');

                $event->sheet->mergeCells('A4:D4');

                $event->sheet->mergeCells('A5:C5');

                $event->sheet->mergeCells('A6:C6');

                $event->sheet->mergeCells('A7:D7');

                $event->sheet->getStyle('A8:D600')->getFont();

                $event->sheet->getStyle('A1:D7')->getAlignment()->setHorizontal('center');

                $event->sheet->getStyle('A8:D10000')->getAlignment()->setHorizontal('left');

            },
        ];
    }

    public function headings(): array
    {
        return [
            [date('d-M-Y h:i A', strtotime('now')), '', '', ''],
            ['Sangamner Nagarpalika Arts, D.J. Malpani Commerce and B.N. Sarada Science','','',''],
            ['College (Autonomous) Sangamner','','',''],
            ['(Affiliated to Savitribai Phule Pune University)', '', '', ''],
            [$this->patternclass, '', '', 'Batch Id : '.''.$this->batch_id,],
            ['Subject : '.''.$this->subject, '', '', 'Internal OUT OF : 20'],
            ['Teacher Name:'.''.$this->faculty_name, '', '', ''],
            ['Sr.No', 'Exam Seat No', 'Name of the Student', 'Marks'],
        ];
    }

    public function map($row): array
    {
        return [
            $this->counter++,
            $row->seatno,
            $row->student->student_name,
        ];

    }

}
