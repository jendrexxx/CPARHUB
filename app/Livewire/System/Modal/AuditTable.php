<?php

namespace App\Livewire\System\Modal;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class AuditTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $auditLogs = DB::table('audit_logs as a')

            // =====================================================
            // USER REPORTED
            // =====================================================
            ->leftJoin('employees as e1', function ($join) {
                $join->whereRaw("
                    FIND_IN_SET(
                        e1.id,
                        REPLACE(
                            REPLACE(
                                REPLACE(a.user_reported, '[', ''),
                                ']', ''
                            ),
                            ' ',
                            ''
                        )
                    )
                ");
            })

            // =====================================================
            // STATUS CHANGED BY
            // =====================================================
            ->leftJoin('employees as e2', function ($join) {
                $join->whereRaw("
                    e2.id = CAST(
                        REPLACE(
                            REPLACE(
                                REPLACE(a.status_changed_by, '[', ''),
                                ']', ''
                            ),
                            ' ',
                            ''
                        ) AS UNSIGNED
                    )
                ");
            })

            // =====================================================
            // SELECT
            // =====================================================
            ->select(
                'a.*',

                DB::raw("
                    GROUP_CONCAT(
                        DISTINCT CONCAT(
                            e1.first_name,
                            ' ',
                            e1.last_name
                        )
                        ORDER BY e1.first_name
                        SEPARATOR ', '
                    ) AS reported_employee
                "),

                DB::raw("
                    MAX(
                        CONCAT(
                            e2.first_name,
                            ' ',
                            e2.last_name
                        )
                    ) AS status_changed_employee
                ")
            )

            // =====================================================
            // SEARCH
            // =====================================================
            ->when($this->search, function ($query) {

                $search = '%' . trim($this->search) . '%';

                $query->where(function ($q) use ($search) {

                    $q->where('a.action', 'like', $search)

                        ->orWhere('a.user_reported_by', 'like', $search)

                        ->orWhere('a.user_reported', 'like', $search)

                        ->orWhere('a.old_value', 'like', $search)

                        ->orWhere('a.new_value', 'like', $search)

                        ->orWhereRaw(
                            "CONCAT(e1.first_name, ' ', e1.last_name) LIKE ?",
                            [$search]
                        )

                        ->orWhereRaw(
                            "CONCAT(e2.first_name, ' ', e2.last_name) LIKE ?",
                            [$search]
                        );
                });
            })

            // =====================================================
            // GROUP
            // =====================================================
            ->groupBy(
                'a.id',
                'a.user_reported_by',
                'a.user_reported',
                'a.action',
                'a.old_value',
                'a.new_value',
                'a.status_changed_by',
                'a.date',
                'a.created_at',
                'a.updated_at'
            )

            // =====================================================
            // ORDER
            // =====================================================
            ->orderBy('a.id', 'asc')

            // =====================================================
            // PAGINATION
            // =====================================================
            ->paginate(
                $this->perPage,
                ['*'],
                'auditPage'
            );

        return view('livewire.system.modal.audit_table', [
            'auditLogs' => $auditLogs,
        ]);
    }
}