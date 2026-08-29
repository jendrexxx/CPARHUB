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

        // Clear existing CPAR data
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
        | CREATE 10 CPAR RECORDS
        |--------------------------------------------------------------------------
        */

        for ($i = 1; $i <= 10; $i++) {

            $dateOpen = Carbon::now()->subDays($i);

            /*
            |--------------------------------------------------------------------------
            | CPAR REQUEST
            |--------------------------------------------------------------------------
            */

            $cparId = DB::table('cpar_request_forms')->insertGetId([
                'cpar_no'             => 'CPAR-2026-QC-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'source_id'           => ($i % 2) + 1,
                'department_id'       => ($i % 5) + 1,
                'reported_by'         => ($i % 5) + 1,

                'complain_name'       => $i % 2 === 0
                    ? 'EMPLOYEE'
                    : 'PATIENT',

                'complainant_name'    => 'Test Complainant ' . $i,

                'concern_category'    => ($i % 3) + 1,

                'concern_description' => 'Sample CPAR concern description for testing record #' . $i,

                'date_open'           => $dateOpen,

                'created_at'          => $dateOpen,
                'updated_at'          => now(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | ASSIGNMENT
            |--------------------------------------------------------------------------
            */

            $employeeNo = 'EMP' . str_pad($i, 4, '0', STR_PAD_LEFT);

            // Different statuses for testing
            $statuses = [
                1,   // PENDING
                15,  // RESOLVED
                18,  // UNRESOLVED
                19,  // RETURNED TO HR
                45,  // FOR HR REVIEW
            ];

            $statusId = $statuses[($i - 1) % count($statuses)];

            $assignmentId = DB::table('cpar_assignments')->insertGetId([
                'cpar_id'            => $cparId,
                'employee_no'        => $employeeNo,
                'dept_head_assigned' => ($i % 5) + 1,
                'department_id'      => ($i % 5) + 1,
                'assigned_to'        => json_encode([
                    $employeeNo
                ]),
                'status_id'          => $statusId,
                'created_at'          => $dateOpen,
                'updated_at'          => now(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | INVESTIGATION
            |--------------------------------------------------------------------------
            */

            DB::table('cpar_investigations')->insert([
                'assigned_id'       => $assignmentId,
                'identified_cause'  => 'Identified cause for CPAR record #' . $i,
                'provided_solution' => 'Provided solution for CPAR record #' . $i,
                'recommendation'    => 'Recommendation for CPAR record #' . $i,
                'action_taken_by'   => 'Department Head ' . $i,
                'date_completed'    => $dateOpen->copy()->addDays(2),
                'tat'               => '2 Days',
                'created_at'        => $dateOpen,
                'updated_at'        => now(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | IR REQUEST
            |--------------------------------------------------------------------------
            */

            $irId = DB::table('cpar_ir_requests')->insertGetId([
                'assignment_ir_id' => $assignmentId,
                'ir_id'            => 'IR-2026-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'employee_no'      => $employeeNo,
                'ir_attachment'    => null,
                'submitted_at'     => $dateOpen->copy()->addDays(2),
                'issued_at'        => $dateOpen->copy()->addDays(2),
                'due_date'         => $dateOpen->copy()->addDays(7),
                'status'           => 'IR SUBMITTED',
                'created_at'       => $dateOpen,
                'updated_at'       => now(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | NTE
            |--------------------------------------------------------------------------
            */

            $nteId = DB::table('cpar_notice_to_explains')->insertGetId([
                'nte_no'             => 'NTE-2026-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'cpar_assignment_id' => $assignmentId,
                'notice_content'     => 'Please provide your written explanation regarding CPAR record #' . $i,
                'status'             => 'FOR EXPLANATION',
                'created_at'         => $dateOpen,
                'updated_at'         => now(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | HISTORY - CREATED
            |--------------------------------------------------------------------------
            */

            DB::table('cpar_histories')->insert([
                'cpar_id'     => $cparId,
                'action'      => 'CPAR CREATED',
                'old_status'  => null,
                'new_status'  => 'PENDING',
                'remarks'     => 'CPAR test record #' . $i . ' created.',
                'created_at'  => $dateOpen,
                'updated_at'  => $dateOpen,
            ]);


            /*
            |--------------------------------------------------------------------------
            | HISTORY - CURRENT STATUS
            |--------------------------------------------------------------------------
            */

            $statusNames = [
                1  => 'PENDING',
                15 => 'RESOLVED',
                18 => 'UNRESOLVED',
                19 => 'RETURNED TO HR',
                45 => 'FOR HR REVIEW',
            ];

            DB::table('cpar_histories')->insert([
                'cpar_id'     => $cparId,
                'action'      => 'STATUS UPDATED',
                'old_status'  => 'PENDING',
                'new_status'  => $statusNames[$statusId],
                'remarks'     => 'Status updated for testing.',
                'created_at'  => $dateOpen->copy()->addDays(2),
                'updated_at'  => $dateOpen->copy()->addDays(2),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | RESULT
        |--------------------------------------------------------------------------
        */

        $this->command->info('======================================');
        $this->command->info('10 CPAR TEST RECORDS CREATED');
        $this->command->info('======================================');

        $this->command->info('CPAR-2026-QC-00001');
        $this->command->info('CPAR-2026-QC-00002');
        $this->command->info('CPAR-2026-QC-00003');
        $this->command->info('CPAR-2026-QC-00004');
        $this->command->info('CPAR-2026-QC-00005');
        $this->command->info('CPAR-2026-QC-00006');
        $this->command->info('CPAR-2026-QC-00007');
        $this->command->info('CPAR-2026-QC-00008');
        $this->command->info('CPAR-2026-QC-00009');
        $this->command->info('CPAR-2026-QC-00010');
    }
}
