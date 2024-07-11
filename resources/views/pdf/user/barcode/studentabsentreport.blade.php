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
 
  
   
 
  
 
     
    <div style="text-align: center; font-weight:bold;color:gray">
      Absent / Copy Case List  Date : {{$pages->first()->examdate}}
 </div>
    
   
    <table style="border-bottom:1pt solid gray; width:680px">
        <thead>
            <th>Sr.No.</th>
            <th> Class</th>
            <th> Subject Name</th>
             
            <th> No. Absent</th> 
          
            <th>No. Copy Case</th>
            <th>No. of Present</th>
               <th>Total</th>
             

        </thead>
        <tbody>
         @foreach($pages as $pagedata)
            <tr>
                <td style=" text-align:  center;"> {{$loop->iteration}}</td>
               <td>
               {{ $pagedata->exam_patternclasses->patternclass->getclass->class_name}}
               </td>
                <td  >
 
                {{$pagedata->subjects->subject_code??''}}         {{$pagedata->subjects->subject_name??''}}   
</td>
          
                <td style=" text-align:  center;">
 
                      {{$pagedata->total_absent??''}} 
                </td>
                <td>
                {{$pagedata->total_copycase??''}} 
            </td>
            <td>
            {{$pagedata->total_present??''}} 
            </td>
                <td style=" text-align:  center;">
                {{$pagedata->total_students??''}} 
            </td>

           
                 
              
                
                 
                
                
              
               
                </tr>
               @endforeach
               <tr>
                <td></td>
                <td></td>
                <td></td>
               
                <td>{{$pages->sum('total_absent')}}</td>
                <td>{{$pages->sum('total_copycase')}}</td>
                <td>{{$pages->sum('total_present')}}</td>
                <td>{{$pages->sum('total_students')}}</td>
               </tr>
              
                
        </tbody>
         
    </table>
 

 
  
    
  
  --}}
