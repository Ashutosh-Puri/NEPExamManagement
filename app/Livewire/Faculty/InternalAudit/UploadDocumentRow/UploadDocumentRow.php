<?php

namespace App\Livewire\Faculty\InternalAudit\UploadDocumentRow;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Facultyinternaldocument;
use Illuminate\Support\Facades\Storage;

class UploadDocumentRow extends Component
{
    use WithFileUploads;

    public $faculty_internal_document_data;

    public $document_to_upload;

    protected function rules()
    {
        return [
            'document_to_upload' => ['required', 'file', 'max:1024', 'mimes:png,jpg,jpeg,pdf'],
        ];
    }

    public function messages()
    {
        return [
            'document_to_upload.required' => 'The document is required.',
            'document_to_upload.file' => 'The document must be a file.',
            'document_to_upload.max' => 'The document must not exceed 1 MB.',
            'document_to_upload.mimes' => 'The document must be a PNG, JPG, JPEG, or PDF file.',
        ];
    }

    public function resetinput()
    {
        $this->document_to_upload = null;
    }

    public function save(Facultyinternaldocument $faculty_internal_document_data)
    {
        $validatedData = $this->validate();
        $document = $this->document_to_upload;

        if (!empty($document)) {

            if ($faculty_internal_document_data) {

                $fileName = $this->generateFileName($faculty_internal_document_data);
                $path = $this->generateFilePath($faculty_internal_document_data);


                $document->storeAs($path, $fileName, 'public');

                $faculty_internal_document_data->update([
                    'document_fileName' => $fileName,
                    'document_filePath' => 'storage/' . $path . $fileName,
                    'updated_at' => now(),
                ]);

                $this->resetinput();
                $this->dispatch('alert', type: 'success', message: 'Document Uploaded Successfully');
                $this->dispatch('form-submitted');
            } else {
                $this->dispatch('alert', type: 'error', message: 'Failed To Upload Document!');
            }
        } else {
            $this->dispatch('alert', type: 'info', message: 'Please wait document is still loading!');
        }
    }

    private function generateFileName($faculty_internal_document_data)
    {
        $doc_name = $this->getValidName($faculty_internal_document_data->internaltooldocument->internaltooldocumentmaster->doc_name);
        $unique_id = uniqid();
        return $doc_name . '_' . $unique_id . '.' . $this->document_to_upload->getClientOriginalExtension();
    }

    private function generateFilePath($faculty_internal_document_data)
    {
        $year_name = $this->getValidName($faculty_internal_document_data->facultysubjecttool->academicyear->year_name);
        $faculty_name = $this->getValidName($faculty_internal_document_data->facultysubjecttool->faculty->faculty_name);
        $subject_code = $this->getValidName($faculty_internal_document_data->facultysubjecttool->subject->subject_code);
        $patternclass_id = $this->getValidName($faculty_internal_document_data->facultysubjecttool->subject->patternclass->id);
        return $year_name . '/' . $faculty_name . '/' . $subject_code . '_' . $patternclass_id . '/';
    }

    private function getValidName($name)
    {
        $name = trim($name);
        $name = str_replace(' ', '_', $name);
        return $name;
    }

    public function mount($facultyinternaldocument)
    {
        $this->faculty_internal_document_data = $facultyinternaldocument;
    }

    public function render()
    {
        return view('livewire.faculty.internal-audit.upload-document-row.upload-document-row')->extends('layouts.faculty')->section('faculty');
    }
}
