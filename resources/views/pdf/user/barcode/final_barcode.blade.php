<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
    @page {
        size: 210mm 302mm;

        margin-left: 0mm;
        margin-top: 7mm;
        margin-bottom: 0;
        margin-right: 0;
    }



    table {
        margin-top: 2mm;
        border-spacing: 0mm;
        margin-top: 0mm;

    }

    td {
        border: 0.1px solid white;
        width: 52mm;

        height: 2.5cm;
        font-size: 12px;
        font-weight: bold;

    }
</style>

@for ($i = 0; $i < $n; $i += 2)
    <table>



        @for ($j = 0; $j < 11; $j += 1)
            @php
                $str = explode(',', $a[$i][$j] ?? ',,,,,,');
                $str1 = explode(',', $a[$i + 1][$j] ?? ',,,,,,');
            @endphp
            <tr>
                @if ($str[0] == 1)
                    <td style="   padding-left:4mm;  " colspan="2">
                        <div style=" margin-left:2mm;height:2.62cm; ">
                            <div>
                                <div> Class : {{ $str[3] }}</div>
                                <div> Sub. {{ substr($str[2], 0, 24) }}</div>

                                <div> Date & Time:@if ($str[4] != '')
                                        {{ date('d-m-Y', strtotime($str[4])) }}-{{ $str[7] }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        </div>

                    </td>
                @else
                    @if ($str[4] != '')
                        <td style="   padding-left:4mm; ">
                            <div style=" margin-left:2mm;height:2.62cm;border-right: 0.1mm dotted black; ">


                                <div> Seat No. {{ $str[3] }}</div>
                                <div> Stk.No. {{ sprintf('%03d', $str[1]) }}
                                    {{ sprintf('%04d', $str[4]) }}
                                </div>
                                <div> Sub. {{ substr($str[2], 0, 20) }}</div>
                            </div>
                        </td>
                        <td style="   padding-left:3mm; ">
                            <div style=" margin-left:2mm;height:2.62cm; ">


                                {{ $str[5] }}-{{ substr($str[2], 0, 10) }}
                                <div style="padding-left:10px;padding-top:3px;">
                                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG(sprintf('%06d', $str[4]), 'C128', 1, 35, [0, 0, 0], true) }}"
                                        alt="barcode" />
                                </div>
                                Semester : {{ $str[6] }}
                            </div>
                        </td>
                    @endif
                @endif


                @if ($str1[0] == 1)
                    <td colspan="2">
                        <div style=" margin-left:0mm;height:2.62cm;border-right: 0.1mm dotted black; ">



                            <div>
                                <div> Class : {{ $str1[3] }}</div>
                                <div> Sub. {{ substr($str1[2], 0, 24) }}</div>

                                <div>Date: @if ($str1[4] != '')
                                        {{ date('d-m-Y', strtotime($str1[4])) }} -{{ $str[7] }}
                                    @endif
                                </div>
                            </div>


                        </div>
                    </td>
                @else
                    @if ($str1[4] != '')
                        <td>
                            <div style=" margin-left:0mm;height:2.62cm;border-right: 0.1mm dotted black; ">
                                <div> Seat No. {{ $str1[3] }}</div>
                                <div> Stk.No. {{ sprintf('%03d', $str1[1]) }}{{ sprintf('%04d', $str1[4]) }} </div>
                                <div> Sub.{{ substr($str1[2], 0, 20) }} </div>
                            </div>
                        </td>
                        <td style="   padding-left:1mm; ">
                            <div style=" margin-left:1mm;height:2.62cm;border-right: 0.1mm dotted black; ">
                                <div> {{ $str1[5] }}-{{ substr($str1[2], 0, 10) }}</div>
                                <div style="padding-left:10px;padding-top:3px;"> <img
                                        src="data:image/png;base64,{{ DNS1D::getBarcodePNG(sprintf('%06d', $str1[4]), 'C128', 1, 35, [0, 0, 0], true) }}"
                                        alt="barcode" />
                                </div>
                                Semester : {{ $str1[6] }}
                            </div>

                        </td>
                    @endif
                @endif
            </tr>
        @endfor
    </table>
@endfor