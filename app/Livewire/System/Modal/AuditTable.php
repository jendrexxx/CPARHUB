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

            // Get reported employees
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

            // Get the user who changed the status
            ->leftJoin('users as u', function ($join) {
                $join->whereRaw("
                u.id = CAST(
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

            // Find the employee linked to the user through email
            ->leftJoin('employees as e2', 'e2.email', '=', 'u.email')

            ->select(
                'a.*',

                // Reported employee names
                DB::raw("
                GROUP_CONCAT(
                    DISTINCT CONCAT_WS(
                        ' ',
                        e1.first_name,
                        e1.last_name
                    )
                    ORDER BY e1.first_name
                    SEPARATOR ', '
                ) AS reported_employee
            "),

                // Prefer the user's name; fallback to the employee's name
                DB::raw("
                MAX(
                    COALESCE(
                        NULLIF(
                            TRIM(
                                CONCAT_WS(
                                    ' ',
                                    e2.first_name,
                                    e2.last_name
                                )
                            ),
                            ''
                        ),
                        u.name
                    )
                ) AS status_changed_employee
            ")
            )

            // Search
            ->when($this->search, function ($query) {
                $search = '%' . trim($this->search) . '%';

                $query->where(function ($q) use ($search) {
                    $q->where('a.action', 'like', $search)
                        ->orWhere('a.user_reported_by', 'like', $search)
                        ->orWhere('a.user_reported', 'like', $search)
                        ->orWhere('a.old_value', 'like', $search)
                        ->orWhere('a.new_value', 'like', $search)

                        ->orWhereRaw("
                        CONCAT_WS(
                            ' ',
                            e1.first_name,
                            e1.last_name
                        ) LIKE ?
                    ", [$search])

                        ->orWhere('u.name', 'like', $search)

                        ->orWhereRaw("
                        CONCAT_WS(
                            ' ',
                            u.first_name,
                            u.last_name
                        ) LIKE ?
                    ", [$search])

                        ->orWhereRaw("
                        CONCAT_WS(
                            ' ',
                            e2.first_name,
                            e2.last_name
                        ) LIKE ?
                    ", [$search]);
                });
            })

            // Group
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

            // Order
            ->orderBy('a.id', 'asc')

            // Pagination
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
