<?php

namespace App\Http\Controllers\Faculty\QuestionPaperBankPdf;

use Mpdf\Mpdf;
use App\Models\Exam;
use App\Models\College;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\Papersubmission;
use App\Models\Questionpaperbank;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Jobs\Faculty\SendQuestionPaperPasswordEmailJob;

class QuestionPaperBankPdfController extends Controller
{
    public function preview_question_paper(Request $request)
    {   
        $questionpaperbank =Questionpaperbank::find($request->questionpaperbank);

        if($questionpaperbank->papersubmission->is_confirmed!==1 && $questionpaperbank->chairman_id === Auth::guard('faculty')->user()->id)
        {
            if(file_exists($questionpaperbank->file_path))
            {
                return response()->file($questionpaperbank->file_path);
            }
            else
            {
                return abort(404);
            }

        }else
        {
            return abort(403);
        }
    }

    public function download_question_paper(Request $request)
    {   
        $questionpaperbank = Questionpaperbank::find($request->questionpaperbank);
    
        if($questionpaperbank && $questionpaperbank->papersubmission->is_confirmed == 1)
        {
            if(file_exists($questionpaperbank->file_path))
            {   
                $apply_watermark_and_download_pdf = false;

                $college=College::where('is_default',1)->first();
                if($college)
                {
                    $setting=Setting::where('college_id',$college->id)->first();
                    if ($setting) 
                    {

                        $apply_watermark_and_download_pdf =$setting->question_paper_apply_watermark;
                    }
                }
               
                if(!$apply_watermark_and_download_pdf)
                {
                    $file_path = $questionpaperbank->file_path;
                    $questionpaperbank->print_date=date('Y-m-d');
                    $questionpaperbank->print_by=Auth::guard('faculty')->user()->id;
                    $questionpaperbank->update();
                    return response()->download($file_path,str_replace(' ', '_', trim($questionpaperbank->file_name)).'.pdf');
                }
                else
                {   
                    $user = Auth::guard('faculty')->user();
                    $password = generate_password();

                    $master_password="Admin@Password";
                    $college=College::where('is_default',1)->first();
                    if($college)
                    {
                        $setting=Setting::where('college_id',$college->id)->first();
                        if($setting)
                        {
                            $master_password=$setting->question_paper_pdf_master_password;
                        }
                    }

                    $watermarkedPdf = $this->addWatermarkToPdfFromPath($questionpaperbank->file_path, $request,$password,$master_password);
                    if ($watermarkedPdf) {
                        $questionpaperbank->print_date=date('Y-m-d');
                        $questionpaperbank->print_by= $user->id;
                        $questionpaperbank->password=$password;
                        $questionpaperbank->update();
                        $data = [
                            'faculty_id' => $user->id,
                            'document_name' => $questionpaperbank->file_name,
                            'password' => $password
                        ];
                        SendQuestionPaperPasswordEmailJob::dispatch($data);
                        $password='';
                        return response()->streamDownload(function () use ($watermarkedPdf) {
                            echo $watermarkedPdf;
                        }, str_replace(' ', '_', trim($questionpaperbank->file_name)).'.pdf');

                    } else {
    
                        return abort(500, 'Failed to add watermark to PDF');
                    }
                   
                }

            }
            else
            {
                return abort(404, 'PDF file not found');
            }
        }
        else
        {
            return abort(403, 'Unauthorized access');
        }
    }
   
    public function addWatermarkToPdfFromPath($pdfPath, $request,$password,$master_password)
    {
        $mpdf = new Mpdf();
        $mpdf->SetProtection(array(), $password, $master_password);
        // $mpdf->SetProtection(array('copy','print','modify','annot-forms','fill-forms','extract','assemble','print-highres'), 'user', 'Admin');
        $watermark = $request->ip('REMOTE_ADDR') . ' - ' . date('d/m/Y h:i:s A');
        $watermarkImagePath = public_path('img/shikshan-logo.png');
    
        if (!file_exists($watermarkImagePath)) {
            return false;
        }

        $mpdf->SetSourceFile($pdfPath);
    
        $pages = $mpdf->SetSourceFile($pdfPath);
        $tplId = [];

        for ($i = 1; $i <= $pages; $i++) {
            $tplId[$i] = $mpdf->ImportPage($i);
        }
    
        foreach ($tplId as $tplidx) {
            $mpdf->UseTemplate($tplidx);
            $imageWidth = 200;
            $imageHeight = 100;
            // $opacity = 0.2;
            // $mpdf->SetAlpha($opacity);
            // $mpdf->Image($watermarkImagePath, 50, 50, $imageWidth, $imageHeight);
            // $mpdf->SetAlpha(1);
            $mpdf->SetWatermarkText($watermark, 0.2);
            $mpdf->showWatermarkText = true;
    
            $mpdf->SetHTMLFooter('<table width="100%"><tr><td width="33%" style="font-size:10px; text-align-center;">{PAGENO}/{nbpg}</td><td width="33%"></td><td width="33%" style="text-align: right; font-size:10px;"> Date : {DATE j-m-Y}</td></tr></table>');
            $mpdf->AddPage();
        }
    
        return $mpdf->Output('', 'S');
    }






    public function faculty_question_paper_bank_report()
    {   
        $papersubmissions = Papersubmission::where('chairman_id', Auth::guard('faculty')->user()->id)->where('is_confirmed', 1)->get();
        $exam=Exam::where('status',1)->first();

        $pdf = new Mpdf();
        $pdf->WriteHTML(view('pdf.faculty.questionpaperbank.question_paper_bank_report_pdf', compact('papersubmissions','exam'))->render());
        
        $pdf->Output('question_paper_bank_report.pdf', 'D');
    }
}
