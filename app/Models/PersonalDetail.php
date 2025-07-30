<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class PersonalDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_number',
        'surname',
        'other_names',
        'date_of_birth',
        'marital_status',
        'phone_number',
        'address',
        'state_of_origin',
        'local_government',
        'ethnic_group',
        'religion',
        'name_of_father',
        'father_state_of_origin',
        'father_place_of_birth',
        'mother_state_of_origin',
        'mother_place_of_birth',
        'applicant_occupation',
        'desired_study_cent',
        'working_experience',
        'has_paid',
        'gender',
        'application_date',
        'application_trxid',
        'application_reference',
        'couse_fee_date',
        'course_fee_reference',
        'course_paid',
        'has_admission',
        'matric_number',
        'course',
        'school',
        'olevel1',
        'olevel2',
        'email',
        'nin',
        'scratchcard_pin_1',
        'scratchcard_serial',
        'scratchcard_upload',
        'passport',
        'amount',
    ];

    public function studentDetail()
    {
        return $this->hasOne(StudentDetail::class, 'id', 'application_number');
    }
    public function educationalDetail()
    {
        return $this->hasOne(EducationalDetail::class, 'application_number', 'id');
    }
    public function bioRegistration()
    {
        return $this->hasOne(BioRegistration::class, 'application_number', 'id');
    }

    public static function generateMatricNumber($program, $centre)
    {
        // Initialize school code
        $courseCode = "UNKNOWN"; // Default value if no match is found

        // Determine the school code using if-else
        if (in_array($program, [
            "Science Laboratory Technology",
        ])) {
            $courseCode = "SLT";
        } elseif (in_array($program, [
            "Public Administration",
            // "Maths / Economics",

        ])) {
            $courseCode = "DPA";
        } elseif (in_array($program, [
            "Business Administration",
            // "Electrical / Electronics",

        ])) {
            $courseCode = "DBA";
        } elseif (in_array($program, [
            "Computer Science",
            // "Geography / Economics",
        ])) {
            $courseCode = "CSC";
        } elseif (in_array($program, [
            "Criminology and intelligence Studies",
            // "English / CRS",

        ])) {
            $courseCode = "CIS";
        } elseif (in_array($program, [
            "Library and Information Science",
        ])) {
            $courseCode = "LIS";
        } elseif (in_array($program, [
            "Media and Communication Studies",
        ])) {
            $courseCode = "MCS";
        } elseif (in_array($program, [
            "Transport Management and Operations",
        ])) {
            $courseCode = "TMO";
        } elseif (in_array($program, [
            "Social/Medical works and Rehabilitation Studies",
        ])) {
            $courseCode = "CSW";
        }

        // Log school code (for debugging)
        Log::debug('SCHOOL CODE', [$courseCode]);

        // Determine the centre code
        if ($centre == 'suleja') {
            $matCentre = 'SUL';
        } elseif ($centre == 'Minna') {
            $matCentre = 'MNA';
        }

        // Get the last matric number for this program
        $latestStudent = self::where('course', $program)
            ->latest('matric_number')
            ->first();

        // Extract the sequential part of the latest matric number
        if ($latestStudent) {
            // Get the last 4 digits instead of 3
            $lastNumber = (int) substr($latestStudent->matric_number, -4);
        } else {
            $lastNumber = 0; // Start from 0 so first number will be 0001
        }

        // Generate the new sequential number (4 digits, padded with zeros)
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        $year = date('y'); // Get the current year (e.g., 25)

        // Return the formatted matric number
        return "D{$year}/{$matCentre}/{$courseCode}/{$newNumber}";
    }
}
