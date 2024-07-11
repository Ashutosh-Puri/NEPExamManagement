    {{-- <style>
        @page {
            margin: 15px 60px 10px 60px;
            overflow: hidden; 
        }

        .page-break {
            page-break-inside: avoid;
            overflow: hidden; 
    page-break-after: always;
}
        
 
       table, td,
        th {
            border: 1px solid gray;
            border-collapse: collapse;
            padding: 5px;
            font-size: 12px !important;
           

        }

 
       
    </style>
 
 @php
     $ccnt=0;
     $abcnt=0;
 @endphp
    @foreach($pages as $page)
 
  
    @foreach($page['barcode']->sortBy('status') as $barcodes)
     
    <div style="text-align: center; font-weight:bold;color:gray">
      Absent / Copy Case List
 </div>
    <table  style="border-bottom:1pt solid gray; ">
    <tr>
            <td width="10px">
                <img
                    src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/unipune.png'))) }}"
                    style="width: 100px; height: 60px  ;"></td>
            <td width="650px" align="center" colspan="3"> Sangamner Nagarpalika Arts, D. J. Malpani Commerce and B. N.
                Sarda Science College (Autonomous) Sangamner <br> (Affiliated to Savitribai Phule Pune University)</td>
            <td width="20px"><img
                    src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/shikshan-logo.png'))) }}"
                    style="width: 100px; height: 60px  ;"></td>
        </tr>
    <tr>
        
            <td colspan="3"> {{ $page['subject']->subjects
                ->patternclass->getclass->class_name }}
                {{$page['subject']->subjects->patternclass
                ->pattern->pattern_name}} <span> Exam: {{$page['subject']->exam_patternclasses->exam->exam_name}}</span></td>
            <td> 
           <div>
           {{ date('d-M-Y', strtotime( $page['subject']->examdate ))}}
           </div>
          <div>
          {{ $page['subject']->timetableslots->timeslot }}
          </div>
                </td>
            <td>Page No:{{  $loop->iteration }} /{{$page['barcode']->count()}}</td>
             
          
        </tr>
        <tr>
            <td colspan="4" align="center">Subject: {{ $page['subject']->subjects->subject_code  }}-{{ $page['subject']->subjects->subject_name  }}
            Semester: {{ $page['subject']->subjects->subject_sem  }}
            </td>
           
           
            
            <td>Max. Marks: {{ $page['subject']->subjects->subject_maxmarks_ext  }} </td>
             
        </tr>
         
    </table>
   
    <table style="border-bottom:1pt solid gray; width:680px">
        <thead>
            <th>Sr.No.</th>
            <th> Barcode No.</th><th> Seat No</th> 
          
            <th>Name</th>
            <th>Mobile No.</th>
               <th>Absent/ Copy Case</th>
             

        </thead>
        <tbody>
         
            @for($i=0;$i<$barcodes->count();$i++) <tr>
                <td style=" text-align:  center;">{{sprintf("%02d",  $i+1)}}</td>
                <td style=" text-align:  center;">
 
                    {{$barcodes[$i]->id??''}}   
                </td>
                <td style=" text-align:  center;">
 
                      {{$barcodes[ $i]->exam_studentseatnos->seatno??''}} 
                </td>
                <td>
            {{$barcodes[ $i]->exam_studentseatnos->student->student_name??''}} 
            </td>
            <td>
            {{$barcodes[ $i]->exam_studentseatnos->student->mobile_no??''}}
            </td>
                <td style=" text-align:  center;">
                   
                @if(isset($barcodes[$i ]))
                @if($barcodes[ $i]->status==1) {{'AB'}}
                @elseif($barcodes[$i]->status==2)
                {{'CC'}}
                @else
                {{""}}
                @endif
            @endif 
            </td>

           
                 
              
                
                 
                
                
              
               
                </tr>
                @endfor
                <tr>
                    @php
                       $abcnt +=$barcodes->where('status','1')->count();
                       $ccnt +=$barcodes->where('status','2')->count();
                    @endphp
                    <td colspan="3">{{'Absent -'}}{{$barcodes->where('status','1')->count()}}</td>
                    <td colspan="1">{{"Copy Case - ".$barcodes->where('status','2')->count()}}</td>
                    <td colspan="2">{{"TOTAL - ".$barcodes->count()}}</td>
                </tr>
                
        </tbody>
         
    </table>
<div class="page-break"></div>@endforeach

 @endforeach
 <table >
    <tr>
        <td>Total Absent - {{$abcnt}}</td>
        <td>Total Copy Case -{{$ccnt}}</td>
        <td>Total - {{$abcnt+$ccnt}}</td>
    </tr>
 </table>
    
  
  --}}
