<?php

namespace App\Imports\User\AdmissionData;

use App\Models\Admissiondata;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class AdmissionDataImport implements ToModel ,WithHeadingRow , WithChunkReading, WithBatchInserts
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function model(array $row)
    { 
        if (isset($this->filters['patternclass_id']) && trim($row['patternclass_id']) != $this->filters['patternclass_id']) {
            return null;
        }

        if (isset($this->filters['subject_ids']) && !in_array(trim($row['subject_id']), $this->filters['subject_ids'])) {
            return null;
        }

        return new Admissiondata([
           'memid'     => $row['memid'],
           'stud_name'    => $row['stud_name'],
           'subject_id'    => $row['subject_id'],
           'user_id'    => $row['user_id'],
           'patternclass_id'    => $row['patternclass_id'],
           'academicyear_id'    => $row['academicyear_id'],
           'college_id'    =>$row['college_id'],
        ]);
    }

    public function chunkSize(): int
    {
        return 50;
    }

    public function batchSize(): int
    {

        return 50;
    }

    public function headingRow(): int
    {
        return 1;
    }
}
