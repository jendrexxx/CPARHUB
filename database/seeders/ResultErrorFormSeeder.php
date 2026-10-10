<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ResultErrorFormSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // Get an employee who can be used as the reporter
            $reporter = DB::table('employees')
                ->whereNotNull('email')
                ->first();

            // Get a department head
            $deptHead = DB::table('employees')
                ->whereNotNull('email')
                ->where('is_lab_sup', '!=', 1)
                ->first();

            // Get priority
            $priority = DB::table('priority_levels')
                ->first();

            // Get department
            $department = DB::table('departments')
                ->first();

            // Stop if required master data does not exist
            if (!$reporter || !$deptHead || !$priority || !$department) {
                $this->command->warn(
                    'Seeder skipped: required employees, priority_levels, or departments data is missing.'
                );

                return;
            }

            $now = Carbon::now();

            $resultId = DB::table('result_error_forms')->insertGetId([
                'result_no'             => 'RESULT-' . now()->format('YmdHis'),
                'employee_no'           => '021-219' ?? null,
                'reported_by'           => 'JENDREX LAGANDO',
                'date_reported'         => $now,
                'patient_name'          => 'Juan Dela Cruz',
                'attending_physician'   => 'Dr. Maria Santos',
                'test_procedure'        => 'Complete Blood Count (CBC)',
                'actual_released_date'  => $now->copy()->subDay(),
                'source_of_information' => 1,
                'data_information'      => json_encode([
                    3,
                ]),
                'technical_information' => json_encode([
                    1
                ]),
                'quality_information'   => json_encode([
                    3
                ]),
                // Change this if your column expects a specific category ID
                'complainant_category'  => 2,
                'complain_name'         => 'Delayed Release of Laboratory Result',
                'concern_description'   => 'Patient reported that the laboratory result was not released within the expected turnaround time.',
                'department_id'         => 9,
                'priority_level'        => 1,
                'created_at'            => $now,
                'updated_at'            => $now,
            ]);

            DB::table('cpar_assignments')->insert([
                'result_id'          => $resultId,
                'dept_head_assigned' => 222,
                'department_id'      => 9,
                'status_id'          => 1,
                'record_type'        => 10,
                'created_by'         => $reporter->id,
                'created_at'         => $now,
                'updated_at'         => $now,
            ]);

            DB::table('cpar_attachments')->insert([
                'result_id'   => $resultId,
                'file_name'   => null,
                'file_path'   => null,
                'file_type'   => null,
                'uploaded_by' => $reporter->id,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);

            $newValue = [
                'result_no'             => 'CPAR-' . $now->format('YmdHis'),
                'date_reported'         => $now,
                'employee_no'           => $reporter->employee_no ?? null,
                'reported_by'           => $reporter->id,

                'patient_name'          => 'Juan Dela Cruz',
                'attending_physician'  => 'Dr. Maria Santos',
                'test_procedure'       => 'Complete Blood Count (CBC)',
                'actual_released_date' => $now->copy()->subDay(),

                'source_of_information' => 1,

                'data_information' => [
                    3,
                ],

                'technical_information' => [
                    1,
                ],

                'quality_information' => [
                    1,
                ],

                'complain_category_id' => 1,
                'complain_name' =>
                'Delayed Release of Laboratory Result',
                'concern_description' =>
                'Patient reported that the laboratory result was not released within the expected turnaround time.',
                'department_id'   => $department->id,
                'department_name' => $department->name ?? null,
                'priority_level'      => $priority->id,
                'dept_head_assigned'  => 222,
                'attachment'         => null,
            ];

            DB::table('audit_logs')->insert([
                'user_reported_by'  => $reporter->id,
                'user_reported'     => $deptHead->id,
                'action'            => 'CREATED',
                'old_value'         => null,
                'new_value'         => json_encode($newValue),
                'status_changed_by' => $reporter->id,
                'date'              => $now,
            ]);


            $this->command->info(
                "CPAR test record created successfully. Result ID: {$resultId}"
            );
        });
    }
}
