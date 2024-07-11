<?php

namespace App\Exports\User\Cap;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SealbagReportExport implements FromView
{
    protected $pages;
    function __construct($pages)
    {
        $this->pages=$pages;
    }
    public function view(): View
    {
        
        return view('pdf.user.barcode.seal_bag_excel_report', [
        
            'pages'=>$this->pages]
        );
    }
}
