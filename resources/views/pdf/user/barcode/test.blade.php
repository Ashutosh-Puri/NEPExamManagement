{{-- <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>

    @page {

        margin-top: .6cm;
        margin-left: 0.25cm;
        margin-bottom: 0;
        margin-right: 0px;
    }
    .page-break {
page-break-before: always;
}
    table {

       
        border-spacing: .3cm 0px;

    }

    td {
        width: 4.74cm;
        height: 2.34cm;
        /*border: 0.01 cm black solid;*/
        font-size: 12px;
         font-weight: bold;


    }
</style>

@for($i=0 ;$i<$n;$i+=2) <table>



    @for($j=0;$j<12;$j+=1)
    @php
   $str= explode(",",$a[$i][$j]??",,,,,,");
   $str1=explode(",",$a[$i+1][$j]??",,,,,,");
   

    @endphp
      <tr> 
       @if($str[0]==1) 
     
       <td colspan="2">
      
           <div style="padding: 10px 10px;">
           <div> Class : {{ $str[3] }}</div>
              <div> Sub. {{ substr($str[2],0,22)}}</div>
           
            <div> Date & Time:@if($str[4] != ""){{date('d-m-Y', strtotime($str[4]))}}-{{ $str[7] }}@endif  </div>
           </div>
           
           </td>
           @else
           @if($str[4] != "")  <td>
             
           <div style="padding: 10px 10px;">
            <div> Seat No. {{ $str[3] }}</div>
            <div> Stk.No. {{sprintf("%03d",  $str[1])  }}
                {{sprintf("%04d", $str[4]) }}   </div>
            <div> Sub. {{ substr($str[2],0,22)}}</div>
           </div>
           </td>
             <td>
             <div style="padding: 10px 10px;">
             {{ $str[5] }}-{{substr($str[2],0,10) }}
            <div style="padding-left:10px;padding-top:3px;">
             <img src="data:image/png;base64,{{DNS1D::getBarcodePNG( sprintf("%06d",$str[4] ), 'C128',1,35,array(0,0,0), true)}}" alt="barcode" /> 
    </div>
    Semester : {{$str[6] }}
             </div>
           </td>
           @endif
           @endif
           
          @if($str1[0]==1) 
        <td colspan="2">
        <div style="padding: 10px 10px;">
         
           <div style="padding: 10px 10px;">
           <div> Class : {{ $str1[3] }}</div>
               <div> Sub. {{ substr($str1[2],0,22)}}</div>
             
            <div>Date: @if($str1[4] != "") {{  date('d-m-Y', strtotime($str1[4]))  }} -{{ $str[7] }}  @endif </div>
           </div>

          
        </div>
           </td>
           @else
           @if($str1[4] != "")
           <td>
           <div style="padding: 10px 10px;">
            <div> Seat No. {{ $str1[3] }}</div>
            <div> Stk.No. {{sprintf("%03d",  $str1[1])  }}{{sprintf("%04d", $str1[4]) }}   </div>
            <div> Sub.{{substr($str1[2],0,22)}} </div>
           </div>
           </td>
           <td>
           <div style="padding: 10px 10px;">
            <div >  {{ $str1[5] }}-{{substr($str1[2],0,10);  }}</div>
            <div style="padding-left:10px;padding-top:3px;"> <img src="data:image/png;base64,{{DNS1D::getBarcodePNG( sprintf("%06d",$str1[4] ), 'C128',1,35,array(0,0,0), true)}}" alt="barcode" /> 
    </div>
    Semester : {{$str1[6] }}
           </div>
             
           </td>
           
           @endif
           @endif
         
           
        </tr>
      
       

        @endfor   
        </table>
        @endfor --}}