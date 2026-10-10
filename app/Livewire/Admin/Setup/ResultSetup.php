<?php

namespace App\Livewire\Admin\Setup;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Component;

#[Layout('components.layouts.app')]
class ResultSetup extends Component
{
    use WithPagination;
    public string $tab = 'result';
    public string $search = '';
    public string $resultTab = 'source';
    public string $complainantSearch = '';
    public string $dataInformationSearch = '';
    public string $technicalSearch = '';
    public string $qualitySearch = '';
    // source of information
    public $sourceName = '';
    public $sourcePage = 1;
    public $editingSourceId = null;
    public $deleteSourceId = null;
    public $deleteSourceName = '';
    // complainant category
    public $complainantCategoryName = '';
    public $complainantPage = 1;
    public $editingComplainantCategoryId = null;
    public $deleteComplainantCategoryId = null;
    public $deleteComplainantCategoryName = '';

    // data information error
    public $dataInformationName = '';
    public $dataInformationPage = 1;
    public $deleteDataInformationId = null;
    public $deleteDataInformationName = '';
    public $editingDataInformationId = null;

    // technical equipment error
    public $technicalName = '';
    public int $technicalPage = 1;
    public $editingTechnicalEquipmentId = null;
    public $deleteTechnicalEquipmentId = null;
    public $deleteTechnicalEquipmentName = '';
    // quality accuracy error
    public $qualityName = '';
    public int $qualityPage = 1;
    public $editingQualityAccuracyId = null;
    public $deleteQualityAccuracyId = null;
    public $deleteQualityAccuracyName = '';

    public function updatingComplainantSearch()
    {
        $this->resetPage();
    }

    public function updatingQualitySearch()
    {
        $this->qualityPage = 1;
    }

    // SOURCE OF INFORMATION
    public function openAddSource()
    {
        $this->reset([
            'sourceName',
            'editingSourceId',
        ]);

        $this->resetValidation();

        $this->modal('add-source')->show();
    }

    public function editSource($id)
    {
        $source = DB::table('result_error_source_of_infos')
            ->where('id', $id)
            ->first();

        if (!$source) {
            return;
        }
        $this->editingSourceId = $source->id;
        $this->sourceName = $source->source_name;
        $this->modal('add-source')->show();
    }

    public function confirmDeleteSource($id)
    {
        $source = DB::table('result_error_source_of_infos')
            ->where('id', $id)
            ->first();

        if (!$source) {
            return;
        }

        $this->deleteSourceId = $source->id;
        $this->deleteSourceName = $source->source_name;

        $this->modal('delete-source')->show();
    }

    public function deleteSource()
    {
        if (!$this->deleteSourceId) {
            return;
        }

        $source = DB::table('result_error_source_of_infos')
            ->where('id', $this->deleteSourceId)
            ->first();

        if (!$source) {
            return;
        }

        DB::table('result_error_source_of_infos')
            ->where('id', $this->deleteSourceId)
            ->delete();

        $this->reset([
            'deleteSourceId',
            'deleteSourceName',
        ]);

        $this->dispatch('modal-close', name: 'delete-source');

        $this->dispatch(
            'toast',
            type: 'success',
            message: 'Source of information deleted successfully.'
        );

        $this->sourcePage = 1;
    }

    public function resetSourceForm()
    {
        $this->reset([
            'sourceName',
            'editingSourceId',
        ]);

        $this->resetValidation();
    }

    public function saveSource()
    {
        $this->validate([
            'sourceName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('result_error_source_of_infos', 'source_name')
                    ->ignore($this->editingSourceId),
            ],
        ]);

        $sourceName = strtoupper(trim($this->sourceName));

        if ($this->editingSourceId) {

            DB::table('result_error_source_of_infos')
                ->where('id', $this->editingSourceId)
                ->update([
                    'source_name' => $sourceName,
                    'updated_at' => now(),
                ]);
            $message = 'Source of information updated successfully.';
        } else {
            DB::table('result_error_source_of_infos')->insert([
                'source_name' => $sourceName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $message = 'Source of information added successfully.';
        }

        $this->reset([
            'sourceName',
            'editingSourceId',
        ]);

        $this->sourcePage = 1;

        $this->dispatch('modal-close', name: 'add-source');

        $this->dispatch(
            'toast',
            type: 'success',
            message: $message
        );
    }

    public function updatedSourceName($value)
    {
        $this->sourceName = strtoupper($value);
    }

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

    public function confirmDeleteComplainantCategory($id)
    {
        $category = DB::table('result_complain_categories')
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

        $category = DB::table('result_complain_categories')
            ->where('id', $this->deleteComplainantCategoryId)
            ->first();

        if (!$category) {
            return;
        }

        DB::table('result_complain_categories')
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

    public function editcomplain($id)
    {
        $category = DB::table('result_complain_categories')
            ->where('id', $id)
            ->first();

        if (!$category) {
            return;
        }
        $this->editingComplainantCategoryId = $category->id;
        $this->complainantCategoryName = $category->complain_name;
        $this->modal('add-complainant-category')->show();
    }

    public function saveComplainantCategory()
    {
        $this->validate([
            'complainantCategoryName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('result_complain_categories', 'complain_name')
                    ->ignore($this->editingComplainantCategoryId),
            ],
        ]);

        $complainName = strtoupper(trim($this->complainantCategoryName));

        if ($this->editingComplainantCategoryId) {

            DB::table('result_complain_categories')
                ->where('id', $this->editingComplainantCategoryId)
                ->update([
                    'complain_name' => $complainName,
                    'updated_at' => now(),
                ]);

            $message = 'Complainant category updated successfully.';
        } else {
            DB::table('result_complain_categories')->insert([
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

    public function updatedComplainantCategoryName($value)
    {
        $this->complainantCategoryName = strtoupper($value);
    }

    // DATA INFORMATION ERROR
    public function deletedata($id)
    {
        $dataInformation = DB::table('result_error_data_informations')
            ->where('id', $id)
            ->first();

        if (!$dataInformation) {
            return;
        }

        $this->deleteDataInformationId = $dataInformation->id;
        $this->deleteDataInformationName = $dataInformation->data_name;

        $this->modal('delete-data-information-error')->show();
    }
    public function deleteDataInformationError()
    {
        if (!$this->deleteDataInformationId) {
            return;
        }

        $dataInformation = DB::table('result_error_data_informations')
            ->where('id', $this->deleteDataInformationId)
            ->first();

        if (!$dataInformation) {
            return;
        }

        DB::table('result_error_data_informations')
            ->where('id', $this->deleteDataInformationId)
            ->delete();

        $this->reset([
            'deleteDataInformationId',
            'deleteDataInformationName',
        ]);

        $this->dataInformationPage = 1;

        $this->modal('delete-data-information-error')->close();

        $this->dispatch(
            'toast',
            type: 'success',
            message: 'Data information error deleted successfully.'
        );
    }

    public function editdata($id)
    {
        $dataInformation = DB::table('result_error_data_informations')
            ->where('id', $id)
            ->first();

        if (!$dataInformation) {
            return;
        }

        $this->editingDataInformationId = $dataInformation->id;
        $this->dataInformationName = $dataInformation->data_name;

        $this->resetValidation();

        $this->modal('add-data-information-error')->show();
    }

    public function openAddDataInformation()
    {
        $this->reset([
            'dataInformationName',
            'editingDataInformationId',
        ]);

        $this->resetValidation();

        $this->modal('add-data-information-error')->show();
    }

    public function saveDataInformationError()
    {
        $this->validate([
            'dataInformationName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('result_error_data_informations', 'data_name')
                    ->ignore($this->editingDataInformationId),
            ],
        ]);

        $dataName = trim($this->dataInformationName);

        if ($this->editingDataInformationId) {

            DB::table('result_error_data_informations')
                ->where('id', $this->editingDataInformationId)
                ->update([
                    'data_name' => $dataName,
                    'updated_at' => now(),
                ]);

            $message = 'Data information error updated successfully.';
        } else {

            DB::table('result_error_data_informations')->insert([
                'data_name' => $dataName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $message = 'Data information error added successfully.';
        }

        $this->reset([
            'dataInformationName',
            'editingDataInformationId',
        ]);

        $this->resetValidation();

        $this->dataInformationPage = 1;

        $this->modal('add-data-information-error')->close();

        $this->dispatch(
            'toast',
            type: 'success',
            message: $message
        );
    }

    public function resetDataInformationForm()
    {
        $this->reset([
            'dataInformationName',
            'editingDataInformationId',
        ]);

        $this->resetValidation();
    }

    // TECHNICAL EQUIPMENT ERROR
    public function openAddTechnicalEquipment()
    {
        $this->reset([
            'technicalName',
            'editingTechnicalEquipmentId',
        ]);

        $this->resetValidation();

        $this->modal('add-technical-equipment')->show();
    }

    public function edittechnical($id)
    {
        $technicalEquipment = DB::table('result_error_technical_equipments')
            ->where('id', $id)
            ->first();

        if (!$technicalEquipment) {
            return;
        }

        $this->editingTechnicalEquipmentId = $technicalEquipment->id;
        $this->technicalName = $technicalEquipment->technical_name;

        $this->resetValidation();

        $this->modal('add-technical-equipment')->show();
    }

    public function deletetechnical($id)
    {
        $technicalEquipment = DB::table('result_error_technical_equipments')
            ->where('id', $id)
            ->first();

        if (!$technicalEquipment) {
            return;
        }

        $this->deleteTechnicalEquipmentId = $technicalEquipment->id;
        $this->deleteTechnicalEquipmentName = $technicalEquipment->technical_name;

        $this->modal('delete-technical-equipment')->show();
    }

    public function deleteTechnicalEquipment()
    {
        if (!$this->deleteTechnicalEquipmentId) {
            return;
        }

        $technicalEquipment = DB::table('result_error_technical_equipments')
            ->where('id', $this->deleteTechnicalEquipmentId)
            ->first();

        if (!$technicalEquipment) {
            return;
        }

        DB::table('result_error_technical_equipments')
            ->where('id', $this->deleteTechnicalEquipmentId)
            ->delete();

        $this->reset([
            'deleteTechnicalEquipmentId',
            'deleteTechnicalEquipmentName',
        ]);

        $this->technicalPage = 1;

        $this->modal('delete-technical-equipment')->close();

        $this->dispatch(
            'toast',
            type: 'success',
            message: 'Technical equipment error deleted successfully.'
        );
    }

    public function saveTechnicalEquipment()
    {
        $this->validate([
            'technicalName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('result_error_technical_equipments', 'technical_name')
                    ->ignore($this->editingTechnicalEquipmentId),
            ],
        ]);

        $technicalName = trim($this->technicalName);

        if ($this->editingTechnicalEquipmentId) {
            DB::table('result_error_technical_equipments')
                ->where('id', $this->editingTechnicalEquipmentId)
                ->update([
                    'technical_name' => $technicalName,
                    'updated_at' => now(),
                ]);

            $message = 'Technical equipment error updated successfully.';
        } else {
            DB::table('result_error_technical_equipments')->insert([
                'technical_name' => $technicalName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $message = 'Technical equipment error added successfully.';
        }

        $this->reset([
            'technicalName',
            'editingTechnicalEquipmentId',
        ]);

        $this->resetValidation();

        $this->technicalPage = 1;

        $this->modal('add-technical-equipment')->close();

        $this->dispatch(
            'toast',
            type: 'success',
            message: $message
        );
    }

    // QUALITY ACCURACY ERROR
    public function openAddQualityAccuracy()
    {
        $this->reset([
            'qualityName',
            'editingQualityAccuracyId',
        ]);

        $this->resetValidation();

        $this->modal('add-quality-accuracy-issue')->show();
    }
    public function editquality($id)
    {
        $qualityAccuracy = DB::table('result_error_quality_accuracies')
            ->where('id', $id)
            ->first();

        if (!$qualityAccuracy) {
            return;
        }

        $this->editingQualityAccuracyId = $qualityAccuracy->id;
        $this->qualityName = $qualityAccuracy->quality_name;

        $this->resetValidation();

        $this->modal('add-quality-accuracy-issue')->show();
    }
    public function deletequality($id)
    {
        $qualityAccuracy = DB::table('result_error_quality_accuracies')
            ->where('id', $id)
            ->first();

        if (!$qualityAccuracy) {
            return;
        }

        $this->deleteQualityAccuracyId = $qualityAccuracy->id;
        $this->deleteQualityAccuracyName = $qualityAccuracy->quality_name;

        $this->modal('delete-quality-accuracy')->show();
    }
    public function deleteQualityAccuracy()
    {
        if (!$this->deleteQualityAccuracyId) {
            return;
        }

        $qualityAccuracy = DB::table('result_error_quality_accuracies')
            ->where('id', $this->deleteQualityAccuracyId)
            ->first();

        if (!$qualityAccuracy) {
            return;
        }

        DB::table('result_error_quality_accuracies')
            ->where('id', $this->deleteQualityAccuracyId)
            ->delete();

        $this->reset([
            'deleteQualityAccuracyId',
            'deleteQualityAccuracyName',
        ]);

        $this->qualityPage = 1;

        $this->modal('delete-quality-accuracy')->close();

        $this->dispatch(
            'toast',
            type: 'success',
            message: 'Quality accuracy error deleted successfully.'
        );
    }

    public function saveQualityAccuracy()
    {
        $this->validate([
            'qualityName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('result_error_quality_accuracies', 'quality_name')
                    ->ignore($this->editingQualityAccuracyId),
            ],
        ]);

        $qualityName = trim($this->qualityName);

        if ($this->editingQualityAccuracyId) {
            DB::table('result_error_quality_accuracies')
                ->where('id', $this->editingQualityAccuracyId)
                ->update([
                    'quality_name' => $qualityName,
                    'updated_at' => now(),
                ]);

            $message = 'Quality accuracy error updated successfully.';
        } else {
            DB::table('result_error_quality_accuracies')->insert([
                'quality_name' => $qualityName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $message = 'Quality accuracy error added successfully.';
        }

        $this->reset([
            'qualityName',
            'editingQualityAccuracyId',
        ]);

        $this->resetValidation();

        $this->qualityPage = 1;

        $this->modal('add-quality-accuracy-issue')->close();

        $this->dispatch(
            'toast',
            type: 'success',
            message: $message
        );
    }
    
    public function resetQualityAccuracyForm()
    {
        $this->reset([
            'qualityName',
            'editingQualityAccuracyId',
        ]);

        $this->resetValidation();
    }

    public function updated($property, $value)
    {
        if ($property === 'complainantCategoryName') {
            $this->complainantCategoryName = strtoupper($value);
        }
        if ($property === 'sourceName') {
            $this->sourceName = strtoupper($value);
        }
    }

    public function render()
    {
        $sourceOfInfos = DB::table('result_error_source_of_infos')
            ->when($this->search, function ($query) {
                $query->where('source_name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        $complainantCategories = DB::table('result_complain_categories')
            ->when($this->complainantSearch, function ($query) {
                $query->where('complain_name', 'like', '%' . $this->complainantSearch . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        $dataInformations = DB::table('result_error_data_informations')
            ->when($this->dataInformationSearch, function ($query) {
                $query->where(
                    'data_name',
                    'like',
                    '%' . $this->dataInformationSearch . '%'
                );
            })
            ->orderByDesc('id')
            ->paginate(
                10,
                ['*'],
                'dataInformationPage'
            );

        $technicalEquipments = DB::table('result_error_technical_equipments')
            ->when($this->technicalSearch, function ($query) {
                $query->where(
                    'technical_name',
                    'like',
                    '%' . $this->technicalSearch . '%'
                );
            })
            ->orderByDesc('id')
            ->paginate(
                10,
                ['*'],
                'technicalPage'
            );

        $qualityAccuracies = DB::table('result_error_quality_accuracies')
            ->when($this->qualitySearch, function ($query) {
                $query->where(
                    'quality_name',
                    'like',
                    '%' . $this->qualitySearch . '%'
                );
            })
            ->orderByDesc('id')
            ->paginate(
                10,
                ['*'],
                'qualityPage'
            );

        return view('livewire.admin.setup.result_setup', [
            'sourceOfInfos' => $sourceOfInfos,
            'complainantCategories' => $complainantCategories,
            'dataInformations' => $dataInformations,
            'technicalEquipments' => $technicalEquipments,
            'qualityAccuracies' => $qualityAccuracies,
        ]);
    }
}
