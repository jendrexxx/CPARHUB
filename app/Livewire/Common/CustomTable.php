<?php

namespace App\Livewire\Common;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class CustomTable extends Component
{
    use WithPagination;

    public string $model;

    public array $columns = [];
    public array $searchable = [];
    public array $visibleColumns = [];

    public string $search = '';
    public int $perPage = 10;

    public string $refreshEvent = 'refreshUsers';
    public ?string $addRoute = null;
    public ?string $addLabel = null;
    public ?string $refreshMethod = null;
    public $loading = false;
    public $error = null;
    public $message;

    protected $paginationTheme = 'tailwind';


    public function mount(
        $model,
        $columns,
        $searchable = [],
        $refreshEvent = null,
        $addRoute = null,
        $addLabel = null,
    ) {
        $this->model = $model;
        $this->columns = $columns;
        $this->searchable = $searchable;
        $this->refreshEvent = $refreshEvent;
        $this->addRoute = $addRoute;
        $this->addLabel = $addLabel;

        foreach ($columns as $field => $label) {
            $this->visibleColumns[$field] = true;
        }

        if ($refreshEvent) {
            $this->dispatch($refreshEvent);
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function refresh()
    {
        if ($this->refreshMethod && method_exists($this, $this->refreshMethod)) {
            $this->{$this->refreshMethod}();
        }

        $this->resetPage();
        $this->dispatch('$refresh');
    }

    #[On('refresh')]
    public function refreshUsers() 
    {  
        $this->loading = true;
        try {
            $response = Http::timeout(5)->withHeaders([
                'X-API-KEY1' => env('API_KEY1'),
                'X-API-KEY2' => env('API_KEY2'),
            ])->get('http://127.0.0.1:8000/api/api_testing_user');
            if ($response->successful()) {
                $data = $response->json();
                $user = $data['data'];
                foreach ($user as $emp) {
                    User::updateOrCreate(
                        ['name' => $emp['name'] ?? null],
                        [
                            'username' => $emp['username'] ?? null,
                            'email' => $emp['email'] ?? null,
                            'password' => $emp['password'] ?? null,
                            'created_at' => $emp['created_at'] ?? null,
                            'updated_at' => $emp['updated_at'] ?? null,
                        ]
                    );
                }
                $this->message = '✅ User synced successfully from API.';
                $this->error = null;
            } else {
                $this->error = '⚠️ API not available, loaded user from database.';
                $this->message = null;
            }
        } catch (\Exception $e) {
            Log::error('user API error: ' . $e->getMessage());
            $this->error = '❌ API not reachable. Loaded user from database.';
            $this->message = null;
        }
        $this->loading = false;
    }

    public function fetchUser()
    {
        $this->loading = true;
        try {
            $response = Http::timeout(5)->withHeaders([
                'X-API-KEY1' => env('API_KEY1'),
                'X-API-KEY2' => env('API_KEY2'),
            ])->get('http://127.0.0.1:8000/api/api_testing_user');
            if ($response->successful()) {
                $data = $response->json();
                $user = $data['data'];
                foreach ($user as $emp) {
                    User::updateOrCreate(
                        ['name' => $emp['name'] ?? null],
                        [
                            'username' => $emp['username'] ?? null,
                            'email' => $emp['email'] ?? null,
                            'password' => $emp['password'] ?? null,
                            'created_at' => $emp['created_at'] ?? null,
                            'updated_at' => $emp['updated_at'] ?? null,
                        ]
                    );
                }
                $this->message = '✅ User synced successfully from API.';
                $this->error = null;
            } else {
                $this->error = '⚠️ API not available, loaded user from database.';
                $this->message = null;
            }
        } catch (\Exception $e) {
            Log::error('user API error: ' . $e->getMessage());
            $this->error = '❌ API not reachable. Loaded user from database.';
            $this->message = null;
        }
        $this->loading = false;
    }

    public function toggleColumnVisibility($column)
    {
        $this->visibleColumns[$column] = ! $this->visibleColumns[$column];
    }

    public function getRecordsProperty()
    {
        /**
         * USER TABLE (WITH JOIN)
         */
        if ($this->model === User::class) {

            $query = User::query()
                ->join('employees as a', 'a.email', '=', 'users.email')

                ->leftJoin('model_has_roles as mhr', function ($join) {
                    $join->on('users.id', '=', 'mhr.model_id')
                        ->where('mhr.model_type', User::class);
                })
                ->leftJoin('roles', 'roles.id', '=', 'mhr.role_id')
                ->select(
                    'users.*',
                    'a.employee_no',
                    'a.position_name',
                    'a.department_name',
                    'a.branch_name',
                    'roles.name as role',
                    DB::raw("CONCAT(a.first_name, ' ', a.last_name) as full_name")
                )->orderBy('a.last_name', 'asc');

            if ($this->search) {
                $query->where(function ($q) {
                    $q->where('a.first_name', 'like', "%{$this->search}%")
                        ->orWhere('a.last_name', 'like', "%{$this->search}%")
                        ->orWhere('a.employee_no', 'like', "%{$this->search}%")
                        ->orWhere('users.username', 'like', "%{$this->search}%")
                        ->orWhere('a.department_name', 'like', "%{$this->search}%")
                        ->orWhere('a.branch_name', 'like', "%{$this->search}%");
                });
            }
            return $query
                ->orderBy('users.created_at', 'desc')
                ->paginate($this->perPage);
        }

        /**
         * DEFAULT TABLE
         */
        $query = ($this->model)::query();

        if ($this->search && count($this->searchable)) {

            $query->where(function ($q) {

                foreach ($this->searchable as $column) {

                    $q->orWhere($column, 'like', "%{$this->search}%");
                }
            });
        }

        return $query->paginate($this->perPage);
    }

    public function editRecord($id)
    {
        $this->dispatch('edit-record', id: $id);
    }

    public function permissionRecord($id)
    {
        $this->dispatch('permission-record', id: $id);
    }

    public function editRole($id)
    {
        $this->dispatch('edit-role', id: $id);
    }

    public function render()
    {
        return view('livewire.common.custom-table', [
            'records' => $this->records,
        ]);
    }
}
