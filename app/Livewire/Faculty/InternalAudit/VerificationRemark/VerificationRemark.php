<?php

namespace App\Livewire\Faculty\InternalAudit\VerificationRemark;

use Livewire\Component;
use App\Models\Facultyinternaldocument;

class VerificationRemark extends Component
{
    public $audit_internal_tool;
    public $verificationremark;

    public $other_verification_remark;

    public $documentViewed = false;

    protected function rules()
    {
        $rules = [
            'verificationremark' => ['required'],
        ];

        if ($this->verificationremark == "Incomplete") {
            $rules['other_verification_remark'] = ['sometimes', 'required'];
        }

        return $rules;
    }

    public function document_viewed()
    {
        $this->documentViewed = true;
    }

     public function messages()
    {
        return [
            'verificationremark.required' => 'The remark field is required.',
            'other_verification_remark.required' => 'The remark field is required.',
        ];
    }

    public function resetinput()
    {
        $this->verificationremark = null;
        $this->documentViewed = false;
    }

    public function save_remark(Facultyinternaldocument $facultyinternaldocument){

        $validatedData = $this->validate();

       // Determine the value to be saved
        $value = $validatedData['verificationremark'];

        if (isset($validatedData['other_verification_remark'])) {
            $value = $validatedData['other_verification_remark'];
        }

        // Update the record with the verified remark
        $facultyinternaldocument->update([
            'verificationremark' => $value,
            'updated_at' => now(),
        ]);

        $facultyinternaldocument->facultysubjecttool()->update([
            'verifybyfaculty_id' => auth()->guard('faculty')->id(),
            'updated_at' => now(),
        ]);

        $this->resetinput();
        $this->dispatch('alert', type: 'success', message: 'Remark Saved Successfully');
        $this->dispatch('remark-saved');
    }

    public function render()
    {
        return view('livewire.faculty.internal-audit.verification-remark.verification-remark')->extends('layouts.faculty')->section('faculty');
    }
}
