<?php

namespace App\Livewire\Admin\Setup;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Component;

#[Layout('components.layouts.app')]
class OtherSetup extends Component
{
    use WithPagination;
    public string $tab = 'complainant';
    public string $othersTab = 'complainant';
    public $complainantSearch = '';
    public $concernSearch = '';
    public $prioritySearch = '';

    public $complainantCategoryName = '';
    public $concernCategoryName = '';
    public $priorityName = '';

    public $editingComplainantCategoryId = null;
    public $editingConcernCategoryId = null;
    public $editingPriorityId = null;

    public $deleteComplainantCategoryId = null;
    public $deleteComplainantCategoryName = '';

    public $deleteConcernCategoryId = null;
    public $deleteConcernCategoryName = '';

    public $deletePriorityId = null;
    public $deletePriorityName = '';
    public $complainantPage = 1;
    public $concernPage = 1;
    public $priorityPage = 1;

    // COMPLAINANT CATEGORY
    public function openAddComplainantCategory()
    {
        $this->reset([
            'complainantCategoryName',
            'editingComplainantCategoryId',
        ]);

        $this->resetValidation();
        $this->modal('add-complainant-category')->show();
    }

    public function editcomplain($id)
    {
        $category = DB::table('cpar_complain_categories')
            ->where('id', $id)
            ->first();

        if (!$category) {
            return;
        }

        $this->editingComplainantCategoryId = $category->id;
        $this->complainantCategoryName = $category->complain_name;

        $this->resetValidation();

        $this->modal('add-complainant-category')->show();
    }

    public function confirmDeleteComplainantCategory($id)
    {
        $category = DB::table('cpar_complain_categories')
            ->where('id', $id)
            ->first();

        if (!$category) {
            return;
        }

        $this->deleteComplainantCategoryId = $category->id;
        $this->deleteComplainantCategoryName = $category->complain_name;

        $this->modal('delete-complainant-category')->show();
    }

    public function deleteComplainantCategory()
    {
        if (!$this->deleteComplainantCategoryId) {
            return;
        }

        $category = DB::table('cpar_complain_categories')
            ->where('id', $this->deleteComplainantCategoryId)
            ->first();

        if (!$category) {
            return;
        }

        DB::table('cpar_complain_categories')
            ->where('id', $this->deleteComplainantCategoryId)
            ->delete();

        $this->reset([
            'deleteComplainantCategoryId',
            'deleteComplainantCategoryName',
        ]);

        $this->complainantPage = 1;

        $this->modal('delete-complainant-category')->close();

        $this->dispatch(
            'toast',
            type: 'success',
            message: 'Complainant category deleted successfully.'
        );
    }

    public function saveComplainantCategory()
    {
        $this->validate([
            'complainantCategoryName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cpar_complain_categories', 'complain_name')
                    ->ignore($this->editingComplainantCategoryId),
            ],
        ]);

        $complainName = strtoupper(trim($this->complainantCategoryName));

        if ($this->editingComplainantCategoryId) {

            DB::table('cpar_complain_categories')
                ->where('id', $this->editingComplainantCategoryId)
                ->update([
                    'complain_name' => $complainName,
                    'updated_at' => now(),
                ]);

            $message = 'Complainant category updated successfully.';
        } else {
            DB::table('cpar_complain_categories')->insert([
                'complain_name' => $complainName,
                'complain_alias' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $message = 'Complainant category added successfully.';
        }

        $this->reset([
            'complainantCategoryName',
            'editingComplainantCategoryId',
        ]);

        $this->resetValidation();

        $this->complainantPage = 1;

        $this->modal('add-complainant-category')->close();

        $this->dispatch(
            'toast',
            type: 'success',
            message: $message
        );
    }

    public function resetComplainantCategoryForm()
    {
        $this->reset([
            'complainantCategoryName',
            'editingComplainantCategoryId',
        ]);

        $this->resetValidation();
    }

    // CONCERN CATEGORY
    public function openAddConcernCategory()
    {
        $this->reset([
            'concernCategoryName',
            'editingConcernCategoryId',
        ]);

        $this->resetValidation();

        $this->modal('add-concern-category')->show();
    }
    public function editConcern($id)
    {
        $concern = DB::table('cpar_concern_categories')
            ->where('id', $id)
            ->first();

        if (!$concern) {
            return;
        }

        $this->editingConcernCategoryId = $concern->id;
        $this->concernCategoryName = $concern->concern_name;

        $this->resetValidation();

        $this->modal('add-concern-category')->show();
    }
    public function saveConcernCategory()
    {
        $this->concernCategoryName = strtoupper(
            trim($this->concernCategoryName)
        );

        $this->validate([
            'concernCategoryName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cpar_concern_categories', 'concern_name')
                    ->ignore($this->editingConcernCategoryId),
            ],
        ]);

        if ($this->editingConcernCategoryId) {

            DB::table('cpar_concern_categories')
                ->where('id', $this->editingConcernCategoryId)
                ->update([
                    'concern_name' => $this->concernCategoryName,
                    'updated_at' => now(),
                ]);

            $message = 'Concern category updated successfully.';
        } else {

            DB::table('cpar_concern_categories')
                ->insert([
                    'concern_name' => $this->concernCategoryName,
                    'concern_alias' => '',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

            $message = 'Concern category added successfully.';
        }

        $this->reset([
            'concernCategoryName',
            'editingConcernCategoryId',
        ]);

        $this->resetValidation();

        $this->concernPage = 1;

        $this->modal('add-concern-category')->close();

        $this->dispatch(
            'toast',
            type: 'success',
            message: $message
        );
    }
    public function resetConcernCategoryForm()
    {
        $this->reset([
            'concernCategoryName',
            'editingConcernCategoryId',
        ]);

        $this->resetValidation();
    }

    public function confirmDeleteConcern($id)
    {
        $concern = DB::table('cpar_concern_categories')
            ->where('id', $id)
            ->first();

        if (!$concern) {
            return;
        }

        $this->deleteConcernCategoryId = $concern->id;
        $this->deleteConcernCategoryName = $concern->concern_name;

        $this->modal('delete-concern-category')->show();
    }

    public function deleteConcern()
    {
        if (!$this->deleteConcernCategoryId) {
            return;
        }

        $concern = DB::table('cpar_concern_categories')
            ->where('id', $this->deleteConcernCategoryId)
            ->first();

        if (!$concern) {
            return;
        }

        DB::table('cpar_concern_categories')
            ->where('id', $this->deleteConcernCategoryId)
            ->delete();

        $this->reset([
            'deleteConcernCategoryId',
            'deleteConcernCategoryName',
        ]);

        $this->concernPage = 1;

        $this->modal('delete-concern-category')->close();

        $this->dispatch(
            'toast',
            type: 'success',
            message: 'Concern category deleted successfully.'
        );
    }

    // PRIORITY LEVEL
    public function openAddPriority()
    {
        $this->reset([
            'priorityName',
            'editingPriorityId',
        ]);

        $this->resetValidation();

        $this->modal('add-priority')->show();
    }

    public function editPriority($id)
    {
        $priority = DB::table('priority_levels')
            ->where('id', $id)
            ->first();

        if (!$priority) {
            return;
        }

        $this->editingPriorityId = $priority->id;
        $this->priorityName = $priority->priority_name;

        $this->resetValidation();

        $this->modal('add-priority')->show();
    }

    public function confirmDeletePriority($id)
    {
        $priority = DB::table('priority_levels')
            ->where('id', $id)
            ->first();

        if (!$priority) {
            return;
        }

        $this->deletePriorityId = $priority->id;
        $this->deletePriorityName = $priority->priority_name;

        $this->modal('delete-priority')->show();
    }

    public function deletePriority()
    {
        if (!$this->deletePriorityId) {
            return;
        }

        $priority = DB::table('priority_levels')
            ->where('id', $this->deletePriorityId)
            ->first();

        if (!$priority) {
            return;
        }

        DB::table('priority_levels')
            ->where('id', $this->deletePriorityId)
            ->delete();

        $this->reset([
            'deletePriorityId',
            'deletePriorityName',
        ]);

        $this->priorityPage = 1;

        $this->modal('delete-priority')->close();

        $this->dispatch(
            'toast',
            type: 'success',
            message: 'Priority deleted successfully.'
        );
    }

    public function savePriority()
    {
        $this->priorityName = strtoupper(
            trim($this->priorityName)
        );

        $this->validate([
            'priorityName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('priority_levels', 'priority_name')
                    ->ignore($this->editingPriorityId),
            ],
        ]);

        if ($this->editingPriorityId) {

            DB::table('priority_levels')
                ->where('id', $this->editingPriorityId)
                ->update([
                    'priority_name' => $this->priorityName,
                    'updated_at' => now(),
                ]);

            $message = 'Priority updated successfully.';
        } else {

            DB::table('priority_levels')
                ->insert([
                    'priority_name' => $this->priorityName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

            $message = 'Priority added successfully.';
        }

        $this->reset([
            'priorityName',
            'editingPriorityId',
        ]);

        $this->resetValidation();

        $this->priorityPage = 1;

        $this->modal('add-priority')->close();

        $this->dispatch(
            'toast',
            type: 'success',
            message: $message
        );
    }

    public function resetPriorityForm()
    {
        $this->reset([
            'priorityName',
            'editingPriorityId',
        ]);

        $this->resetValidation();
    }

    public function render()
    {
        $complainantCategories = DB::table('cpar_complain_categories')
            ->when($this->complainantSearch, function ($query) {
                $query->where(
                    'complain_name',
                    'like',
                    '%' . $this->complainantSearch . '%'
                );
            })
            ->orderByDesc('id')
            ->paginate(
                10,
                ['*'],
                'complainantPage'
            );

        $concernCategories = DB::table('cpar_concern_categories')
            ->when($this->concernSearch, function ($query) {
                $query->where(
                    'concern_name',
                    'like',
                    '%' . $this->concernSearch . '%'
                );
            })
            ->orderByDesc('id')
            ->paginate(
                10,
                ['*'],
                'concernPage'
            );

        $priorities = DB::table('priority_levels')
            ->when($this->prioritySearch, function ($query) {
                $query->where(
                    'priority_name',
                    'like',
                    '%' . $this->prioritySearch . '%'
                );
            })
            ->orderByDesc('id')
            ->paginate(
                10,
                ['*'],
                'priorityPage'
            );

        return view('livewire.admin.setup.other_setup', [
            'complainantCategories' => $complainantCategories,
            'concernCategories' => $concernCategories,
            'priorities' => $priorities,
        ]);
    }
}
