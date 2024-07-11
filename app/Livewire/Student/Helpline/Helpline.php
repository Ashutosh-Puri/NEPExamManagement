<?php

namespace App\Livewire\Student\Helpline;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Studenthelpline;
use Illuminate\Validation\Rule;
use App\Models\Studenthelplinequery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class Helpline extends Component
{   
    use WithFileUploads;
    use WithPagination;

    public $mode='all';
    public $perPage=10;
    public $search='';
    public $sortColumn="updated_at";
    public $sortColumnBy="ASC";
    public $ext;

    public $helplinequeries;
    public $student_helpline_query_id;
    public $old_query;
    public $new_query;
    public $description;
    public $current_query="Query";
    public $uploaded_documents=[];
    public $uploaded_documents_old=[];
    public $edit_id;
    public $documents =[];

    protected function rules()
    {
        $rules = [
            'student_helpline_query_id' => ['required', 'numeric', Rule::exists('student_helpline_queries', 'id')],
            'old_query' => ['required', 'string','max:255'],
            'new_query' => ['required', 'string','max:255'],
            'description' => ['nullable', 'string','max:400'],
        ];

        if(count($this->documents) >0)
        {   
            // $helpline = Studenthelpline::find($this->edit_id);
            foreach ($this->documents as $doc) {
                // if(null!==($helpline->studenthelplineuploadeddocuments()->where('helpline_document_id',$doc->id)->first()))
                // {
                //     $rules["uploaded_documents.".$doc->id] = ['nullable','image', 'max:300','mimes:png,jpg,jpeg'];
                // }else
                // {
                    $rules["uploaded_documents.".$doc->id] = ['required','file', 'max:1024','mimes:png,jpg,jpeg,pdf'];
                // }
            }
        }

        return $rules;
    }

    public function messages()
    {   
        $messages = [
            'student_helpline_query_id.required' => 'Query Feild  is required.',
            'student_helpline_query_id.exists' => 'The selected Query is invalid.',
            'old_query.required' => 'The Current '.$this->current_query.' field is required.',
            'old_query.string' => 'The Current '.$this->current_query.' must be a string.',
            'old_query.max' => 'The Current '.$this->current_query.' must not exceed 255 characters.',
            
            'new_query.required' => 'The New '.$this->current_query.' field is required.',
            'new_query.string' => 'The New '.$this->current_query.' must be a string.',
            'new_query.max' => 'The New '.$this->current_query.' must not exceed 255 characters.',

            'description.required' => 'The Description field is required.',
            'description.string' => 'The Description must be a string.',
            'description.max' => 'The Description must not exceed 400 characters.',
        ];
        if(count($this->documents) >0)
        {
            foreach ($this->documents as $doc) {
                $messages["uploaded_documents.".$doc->id.".required"] = "The ".$doc->document_name." is required.";
                $messages["uploaded_documents.".$doc->id.".image"] = "The ".$doc->document_name." must be an image.";
                $messages["uploaded_documents.".$doc->id.".max"] = "The ".$doc->document_name." must not exceed :max KB.";
                $messages["uploaded_documents.".$doc->id.".mimes"] = "The ".$doc->document_name." must be in PNG, JPG,JPEG or PDF format.";
            }   

        }    

        return $messages;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        if($propertyName=='student_helpline_query_id'){
            $this->uploaded_documents=[];
        }
    }
    public function resetinput()
    {
        $this->student_helpline_query_id=null;
        $this->old_query=null;
        $this->new_query=null;
        $this->description=null;
        $this->edit_id=null;
        $this->documents=[];
        $this->uploaded_documents = [];
        $this->uploaded_documents_old=[];
    }

    public function sort_column($column)
    {
        if( $this->sortColumn === $column)
        {
            $this->sortColumnBy=($this->sortColumnBy=="ASC")?"DESC":"ASC";
            return;
        }
        $this->sortColumn=$column;
        $this->sortColumnBy=="ASC";
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function setmode($mode)
    {
        if($mode=='add')
        {
            $this->resetinput();
        }
        $this->mode=$mode;
    }

    // public function export()
    // {
    //     $filename="Faculty-".now();
    //     switch ($this->ext) {
    //         case 'xlsx':
    //             return Excel::download(new ExportFaculty($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
    //         break;
    //         case 'csv':
    //             return Excel::download(new ExportFaculty($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
    //         break;
    //         case 'pdf':
    //             return Excel::download(new ExportFaculty($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
    //         break;
    //     }

    // }

    public function add()
    {
        $this->validate();

        $helpline = Auth::guard('student')->user()->studenthelplines()->create([
            'student_helpline_query_id' => $this->student_helpline_query_id,
            'old_query' => $this->old_query,
            'new_query' => $this->new_query,
            'description' => $this->description,
            'status' => 0,
        ]);
    
        $path = 'student/helpline/documents/';
        foreach ($this->uploaded_documents as $key => $document) {
            $fileName = 'document-' . time().'-'.$key.'.' .  $document->getClientOriginalExtension();
            $document->storeAs($path, $fileName, 'public');
            $helpline->studenthelplineuploadeddocuments()->create([
                'helpline_document_id' =>  $key,
                'helpline_document_path' => 'storage/' . $path . $fileName,
            ]);
        }
        
        $this->resetinput();
        $this->setmode('all');
        $this->dispatch('alert',type:'success',message:'Helpline Request Created Successfully !!');
    }

    public function view(Studenthelpline $helpline)
    {   
    
        $this->uploaded_documents_old=[];
        $this->edit_id=  $helpline->id;
        $this->student_helpline_query_id=  $helpline->student_helpline_query_id;
        $this->old_query=  $helpline->old_query;
        $this->new_query=  $helpline->new_query;
        $this->description=  $helpline->description;

        $documents=$helpline->studenthelplineuploadeddocuments()->get();
        foreach($documents as $doc)
        {
            $this->uploaded_documents_old[$doc->helpline_document_id ]=$doc->helpline_document_path;
        }
        $this->setmode('view');
    }

    // public function edit(Studenthelpline $helpline)
    // {   
    //     $this->resetinput();
    //     $this->uploaded_documents_old=[];
    //     $this->edit_id=  $helpline->id;
    //     $this->student_helpline_query_id=  $helpline->student_helpline_query_id;
    //     $this->old_query=  $helpline->old_query;
    //     $this->new_query=  $helpline->new_query;
    //     $this->description=  $helpline->description;

    //     $documents=$helpline->studenthelplineuploadeddocuments()->get();
    //     foreach($documents as $doc)
    //     {
    //         $this->uploaded_documents_old[$doc->helpline_document_id ]=$doc->helpline_document_path;
    //     }
    //     $this->setmode('edit');
    // }

    // public function update(Studenthelpline $helpline)
    // {
    //     $this->validate();
    //     $helpline->update([
    //         'student_helpline_query_id' => $this->student_helpline_query_id,
    //         'old_query' => $this->old_query,
    //         'new_query' => $this->new_query,
    //         'description' => $this->description,
    //         'status' => 0,
    //     ]);
        
    //     $path = 'student/helpline/documents/';
    //     foreach ($this->uploaded_documents as $key => $document) {
    //         $fileName = 'document-' . time().'-'.$key.'.' .  $document->getClientOriginalExtension();
    //         $document->storeAs($path, $fileName, 'public');
    //         $existingDocument = $helpline->studenthelplineuploadeddocuments()->where('helpline_document_id', $key)->first();

    //         if ($existingDocument) {
    //             File::delete($existingDocument->helpline_document_path);
    //             $existingDocument->update(['helpline_document_path' => 'storage/' . $path . $fileName]);
    //         } else {
    //             $helpline->studenthelplineuploadeddocuments()->create([
    //                 'helpline_document_id' => $key,
    //                 'helpline_document_path' => 'storage/' . $path . $fileName,
    //             ]);
    //         }
    //     }
    //     $this->resetinput();
    //     $this->setmode('all');
    //     $this->dispatch('alert',type:'success',message:'Helpline Request Updated Successfully !!');

    // }

    public function cancel(Studenthelpline $helpline)
    {
        $helpline->status=3;
        $helpline->update();
        $this->dispatch('alert',type:'success',message:'Helpline Status Updated Successfully !!');

    }

    public function render()
    {     
        if ($this->student_helpline_query_id) {

            $query = Studenthelplinequery::findOrFail($this->student_helpline_query_id);
            $this->current_query= $query->query_name;
            $this->documents = $query->studenthelplinedocuments()->get(); 
        }

        $this->helplinequeries = Studenthelplinequery::where('is_active', 1)->get();

        $student_helplines=Studenthelpline::where('student_id',Auth::guard('student')->user()->id)->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);
        return view('livewire.student.helpline.helpline', compact('student_helplines'))->extends('layouts.student')->section('student');
    }

}
