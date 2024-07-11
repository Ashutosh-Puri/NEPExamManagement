<?php

namespace App\Livewire\User\Setting;

use App\Models\College;
use App\Models\Setting;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class SiteSetting extends Component
{   
    public $show_abcid;
    public $abcid_required;
    public $question_paper_pdf_master_password;
    public $statement_of_marks_is_year_wise;
    public $question_paper_apply_watermark;
    public $exam_time_interval;
    public $setting;
    public $storage_link=false;
    public $laravel_log=false;
    public $cacheStatus = [];


    public function toggleValue($property)
    {   

        DB::beginTransaction();

        try
        {   

            $this->setting->{$property} = !$this->setting->{$property};
            $this->setting->user_id=Auth::guard('user')->user()->id;
            $this->setting->update();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Setting Saved Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Save Setting !!');
        }
        

       
    }

    public function updateValue($property)
    {   
        DB::beginTransaction();

        try
        {   

            $this->setting->{$property} = $this->{$property};
            $this->setting->user_id=Auth::guard('user')->user()->id;
            $this->setting->update();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Setting Saved Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Save Setting !!');
        }
    }

    public function deleteSymbolicLinkAndCreateNew()
    {
        $linkPath = public_path('storage');

        if (File::exists($linkPath)) 
        {
            File::delete($linkPath);
        }

        $target = storage_path('app/public');
        File::link($target, $linkPath);

        Artisan::call('cache:clear');
        Artisan::call('storage:link');
        Artisan::call('cache:clear');

        $this->dispatch('alert',type:'success',message:'New Fresh Symbolic Link Created Successfully !!');
    }

    public function downloadLogFile()
    {
        $logFilePath = storage_path('logs/laravel.log');

        if (file_exists($logFilePath)) {
            return response()->download($logFilePath, 'laravel.log');
        } else {

            $this->dispatch('alert',type:'info',message:'Log File Not Found.');
        }
    }

    public function clearLogFile()
    {
        $logFilePath = storage_path('logs/laravel.log');

        if (file_exists($logFilePath)) {
            file_put_contents($logFilePath, '');
            $this->dispatch('alert',type:'success',message:'Log file cleared successfully. !!');
        } else {
            $this->dispatch('alert',type:'info',message:'Log File Not Found.');
        }
    }

    public function logFileHasContent()
    {
        $logFilePath = storage_path('logs/laravel.log');

        if (file_exists($logFilePath)) 
        {
            $logContent = file_get_contents($logFilePath);

            return !empty($logContent);
        }

        return false;
    }


    public function clearCache()
    {

        Artisan::call("allcls");
        $this->dispatch('alert',type:'success',message:'Cache Cleared successfully. !!');
    }

    
    public function render()
    {   
        $college=College::select('id')->where('is_default',1)->first();
        if($college)
        {
            $this->setting=Setting::where('college_id',$college->id)->first();
            $this->show_abcid=$this->setting->show_abcid==1?true:false;
            $this->abcid_required=$this->setting->abcid_required==1?true:false;
            $this->question_paper_pdf_master_password=$this->setting->question_paper_pdf_master_password;
            $this->statement_of_marks_is_year_wise=$this->setting->statement_of_marks_is_year_wise;
            $this->question_paper_apply_watermark=$this->setting->question_paper_apply_watermark==1?true:false;
            $this->exam_time_interval=$this->setting->exam_time_interval;

        
            if (file_exists(public_path('storage'))) {
                $this->storage_link=true;
            }

            if ($this->logFileHasContent()) {
               $this->laravel_log=true;
            }
        }

        return view('livewire.user.setting.site-setting')->extends('layouts.user')->section('user');
    }
}
