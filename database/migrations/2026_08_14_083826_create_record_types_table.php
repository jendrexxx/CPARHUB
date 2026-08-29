<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CparTestSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        /*
        |--------------------------------------------------------------------------
        | CLEAR CPAR DATA
        |--------------------------------------------------------------------------
        */

        DB::table('cpar_attachments')->truncate();
        DB::table('cpar_employee_disciplinary_records')->truncate();
        DB::table('cpar_histories')->truncate();
        DB::table('cpar_investigations')->truncate();
        DB::table('cpar_ir_requests')->truncate();
        DB::table('cpar_ir_responses')->truncate();
        DB::table('cpar_notice_to_explains')->truncate();
        DB::table('cpar_nte_responses')->truncate();
        DB::table('cpar_assignments')->truncate();
        DB::table('cpar_request_forms')->truncate();

        Schema::enableForeignKeyConstraints();


        /*
        |--------------------------------------------------------------------------
        | STATUS TEST DATA
        |--------------------------------------------------------------------------
        */

        $statuses = [
            1  => 'PENDING',
            15 => 'RESOLVED',
            18 => 'UNRESOLVED',
            19 => 'RETURNED TO HR',
            45 => 'FOR HR REVIEW',
        ];


        /*
        |--------------------------------------------------------------------------
        | CREATE 10 CPAR RECORDS
        |--------------------------------------------------------------------------
        */

        for ($i = 1; $i <= 10; $i++) {

            $dateOpen = Carbon::now()->subDays($i);

            $employeeNo = 'EMP' . str_pad($i, 4, '0', STR_PAD_LEFT);

            $departmentId = (($i - 1) % 5) + 1;

            $statusIds = array_keys($statuses);

            $statusId = $statusIds[($i - 1) % count($statusIds)];


            /*
            |--------------------------------------------------------------------------
            | 1. CPAR REQUEST FORM
            |--------------------------------------------------------------------------
            */

            $cparId = DB::table('cpar_request_forms')->insertGetId([

                'cpar_no' => 'CPAR-2026-QC-' .
                    str_pad($i, 5, '0', STR_PAD_LEFT),

                'employee_no' => $employeeNo,

                'reported_by' => 'EMP' .
                    str_pad((($i % 5) + 1), 4, '0', STR_PAD_LEFT),

                'date_open' => $dateOpen->format('Y-m-d H:i:s'),

                'source_id' => (($i - 1) % 2) + 1,

                'complaint_category_id' => (($i - 1) % 3) + 1,

                'concern_category_id' => (($i - 1) % 3) + 1,

                'complainant_name' => 'Test Complainant ' . $i,

                'concern_description' =>
                    'Sample CPAR concern description for testing record #' . $i,

                'department_id' => $departmentId,

                'priority_level' => $i % 2 === 0
                    ? 'HIGH'
                    : 'NORMAL',

                'created_by' => 1,

                'created_at' => $dateOpen,

                'updated_at' => now(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | 2. CPAR ASSIGNMENT
            |--------------------------------------------------------------------------
            */

            $assignmentId = DB::table('cpar_assignments')->insertGetId([

                'cpar_id' => $cparId,

                'employee_no' => $employeeNo,

                'dept_head_assigned' => (($i - 1) % 5) + 1,

                'department_id' => $departmentId,

                'assigned_to' => json_encode([
                    $employeeNo
                ]),

                'assigned_date' => $dateOpen
                    ->copy()
                    ->addDay(),

                'status_id' => $statusId,

                'remarks' =>
                    'Test assignment remarks for CPAR #' . $i,

                'record_type' => 1,

                'created_by' => 1,

                'created_at' => $dateOpen,

                'updated_at' => now(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | 3. INVESTIGATION
            |--------------------------------------------------------------------------
            */

            DB::table('cpar_investigations')->insert([

                'assigned_id' => $assignmentId,

                'identified_cause' =>
                    'Identified cause for CPAR record #' . $i .
                    '. Employee failed to follow the established procedure.',

                'provided_solution' =>
                    'Corrective action was provided for CPAR record #' . $i .
                    '. Employee was reminded of the proper procedure.',

                'recommendation' =>
                    'Conduct regular monitoring and refresher training for record #' . $i,

                'action_taken_by' =>
                    'Department Head ' . $i,

                'date_completed' =>
                    $dateOpen->copy()->addDays(2)->format('Y-m-d'),

                'tat' => '2 Days',

                'remarks' =>
                    'Investigation completed for test CPAR #' . $i,

                'created_at' => $dateOpen,

                'updated_at' => now(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | 4. IR REQUEST
            |--------------------------------------------------------------------------
            */

            DB::table('cpar_ir_requests')->insert([

                'assignment_ir_id' => $assignmentId,

                'ir_id' =>
                    'IR-2026-' . str_pad($i, 5, '0', STR_PAD_LEFT),

                'employee_no' => $employeeNo,

                'ir_attachment' => null,

                'issued_at' =>
                    $dateOpen->copy()->addDays(2)->format('Y-m-d H:i:s'),

                'due_date' =>
                    $dateOpen->copy()->addDays(7)->format('Y-m-d H:i:s'),

                'status' => 'IR SUBMITTED',

                'submitted_at' =>
                    $dateOpen->copy()->addDays(2)->format('Y-m-d H:i:s'),

                'created_at' => $dateOpen,

                'updated_at' => now(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | 5. NTE
            |--------------------------------------------------------------------------
            */

            DB::table('cpar_notice_to_explains')->insert([

                'assignment_id' => $assignmentId,

                'nte_no' =>
                    'NTE-2026-' . str_pad($i, 5, '0', STR_PAD_LEFT),

                'nte_attachment' => null,

                'issued_at' =>
                    $dateOpen->copy()->addDays(3)->format('Y-m-d H:i:s'),

                'due_date' =>
                    $dateOpen->copy()->addDays(8)->format('Y-m-d H:i:s'),

                'status' => 'FOR EXPLANATION',

                'created_by' => 1,

                'created_at' => $dateOpen,

                'updated_at' => now(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | 6. ATTACHMENT
            |--------------------------------------------------------------------------
            */

            DB::table('cpar_attachments')->insert([

                'cpar_id' => $cparId,

                'file_name' =>
                    'sample_attachment_' . $i . '.pdf',

                'file_path' => null,

                'file_type' => 'application/pdf',

                'uploaded_by' => 1,

                'created_at' => $dateOpen,

                'updated_at' => now(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | 7. HISTORY - CREATED
            |--------------------------------------------------------------------------
            */

            DB::table('cpar_histories')->insert([

                'user' => '1',

                'action' => 'CPAR CREATED',

                'old_value' => null,

                'new_value' => json_encode([
                    'cpar_id' => $cparId,
                    'status' => 'PENDING',
                ]),

                'date' => $dateOpen->format('Y-m-d H:i:s'),

                'created_at' => $dateOpen,

                'updated_at' => $dateOpen,
            ]);


            /*
            |--------------------------------------------------------------------------
            | 8. HISTORY - STATUS
            |--------------------------------------------------------------------------
            */

            DB::table('cpar_histories')->insert([

                'user' => '1',

                'action' => 'STATUS UPDATED',

                'old_value' => json_encode([
                    'status' => 'PENDING',
                ]),

                'new_value' => json_encode([
                    'status' => $statuses[$statusId],
                ]),

                'date' =>
                    $dateOpen->copy()->addDays(2)->format('Y-m-d H:i:s'),

                'created_at' =>
                    $dateOpen->copy()->addDays(2),

                'updated_at' =>
                    $dateOpen->copy()->addDays(2),
            ]);


            /*
            |--------------------------------------------------------------------------
            | 9. DISCIPLINARY RECORD
            |--------------------------------------------------------------------------
            */

            if (in_array($statusId, [15, 18, 45])) {

                DB::table('cpar_employee_disciplinary_records')->insert([

                    'assignment_id' => $assignmentId,

                    'discipline_ids' => json_encode([
                        (($i - 1) % 3) + 1
                    ]),

                    'offense_ids' => json_encode([
                        (($i - 1) % 3) + 1
                    ]),

                    'decision_ids' => json_encode([
                        (($i - 1) % 6) + 1
                    ]),

                    'incident_date' =>
                        $dateOpen->format('Y-m-d'),

                    'valid_until' =>
                        $dateOpen->copy()->addMonths(6)->format('Y-m-d'),

                    'remarks' =>
                        'Test disciplinary remarks for CPAR #' . $i,

                    'management_remarks' =>
                        'Test management remarks for CPAR #' . $i,

                    'created_by' => 1,

                    'status' => $statusId === 45
                        ? 'FOR HR REVIEW'
                        : 'COMPLETED',

                    'created_at' => $dateOpen,

                    'updated_at' => now(),
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | DONE
        |--------------------------------------------------------------------------
        */

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('  CPAR TEST DATA CREATED SUCCESSFULLY');
        $this->command->info('========================================');
        $this->command->info('Total CPAR Records: 10');
        $this->command->info('');
    }
}