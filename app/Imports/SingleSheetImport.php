<?php

namespace App\Imports;

use App\Models\PersonalDetail;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SingleSheetImport implements ToModel, WithHeadingRow, WithStartRow
{
    protected $centre;
    protected $course;

    public function __construct($centre, $sheetName)
    {
        $this->centre = $centre;
        $this->course = strtoupper(trim($sheetName));
    }

    public function startRow(): int
    {
        return 3; // Student data starts at row 3
    }

    public function model(array $row)
    {
        if (!isset($row['matric_number']) || !isset($row['name'])) {
            return null;
        }

        $matricNumber = $row['matric_number'];

        $existingStudent = PersonalDetail::where('matric_number', $matricNumber)->first();

        if ($existingStudent) {
            $existingStudent->update([
                'other_names' => $row['name'],
                'course' => $this->course,
                'desired_study_cent' => $this->centre,
                'has_admission' => true,
            ]);
            return null;
        }

        return new PersonalDetail([
            'matric_number' => $matricNumber,
            'application_number' => $matricNumber,
            'other_names' => $row['name'],
            'course' => $this->course,
            'desired_study_cent' => $this->centre,
            'has_admission' => true,
        ]);
    }
}
