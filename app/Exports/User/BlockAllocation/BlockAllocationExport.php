<?php

namespace App\Exports\User\BlockAllocation;

use App\Classroom;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Exam;
use App\Models\Examtimetable;
use App\Models\Timetableslot;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class BlockAllocationExport implements FromView ,ShouldAutoSize ,WithEvents ,WithHeadings
{

    protected $examdate,$timeslot_id;

    function __construct($examdate,$timeslot_id)
    {
        
        $this->examdate=$examdate;
        $this->timeslot_id=$timeslot_id;
    }

    public function registerEvents(): array
    {
        return[

            AfterSheet::class=>function (AfterSheet $event)
            {
                $event->sheet->getStyle('A1:D100')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'font' => [
                        'bold' => true,
                    ],
                ]);
                
                $event->sheet->getStyle('C')->getAlignment()->setWrapText(true);
            }
        ];
    }

    public function headings(): array
    {
        return $this->headings;
    }


    public function view(): View
    {
        $exam=Exam::where('status',1)->first();

        $timeslot=Timetableslot::find($this->timeslot_id);
        $time_slot=$timeslot->timeslot;
   
        $exam_time_tables=Examtimetable::where('examdate',$this->examdate)->where('timeslot_id',$this->timeslot_id)->where('status',1)->get();
       
        return view('pdf.user.block_allocation.block_allocation_excel', ['exam_time_tables'=>$exam_time_tables,'exam'=>$exam,'examdate'=>$this->examdate,'time_slot'=>$time_slot]);
    }
}
