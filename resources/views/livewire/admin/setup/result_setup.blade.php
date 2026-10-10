<div class="space-y-6">

    <div>
        <flux:heading size="lg">
            Result Setup
        </flux:heading>

        <flux:subheading>
            Manage settings and configurations related to Result records.
        </flux:subheading>
    </div>

    <flux:separator />
    @include('toast')
    <div class="grid grid-cols-12 gap-6">

        {{-- RESULT SETUP SIDEBAR --}}
        <div class="col-span-12 md:col-span-4">

            <div class="rounded-xl border border-zinc-200 bg-white p-2 dark:border-zinc-700 dark:bg-zinc-900">

                <flux:navlist>

                    <flux:navlist.item
                        wire:click="$set('resultTab', 'source')"
                        icon="document-text"
                        :current="$resultTab === 'source'">

                        Source Of Information

                    </flux:navlist.item>

                    <flux:navlist.item
                        wire:click="$set('resultTab', 'complainant')"
                        icon="tag"
                        :current="$resultTab === 'complainant'">

                        Complainant Categories

                    </flux:navlist.item>


                    <flux:navlist.item
                        wire:click="$set('resultTab', 'data-error')"
                        icon="exclamation-triangle"
                        :current="$resultTab === 'data-error'">

                        Data Information Error

                    </flux:navlist.item>


                    <flux:navlist.item
                        wire:click="$set('resultTab', 'technical')"
                        icon="wrench-screwdriver"
                        :current="$resultTab === 'technical'">

                        Technical Equipment Issues

                    </flux:navlist.item>


                    <flux:navlist.item
                        wire:click="$set('resultTab', 'quality')"
                        icon="check-badge"
                        :current="$resultTab === 'quality'">

                        Quality Accuracy Issues

                    </flux:navlist.item>

                </flux:navlist>

            </div>

        </div>

        {{-- RESULT SETUP CONTENT --}}
        <div class="col-span-12 md:col-span-8">

            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">

                @if($resultTab === 'source')

                <div class="space-y-4">

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                        <div>
                            <flux:heading size="md">
                                Source Of Information
                            </flux:heading>

                            <flux:subheading class="mt-1">
                                Manage available sources of information.
                            </flux:subheading>
                        </div>

                        <flux:button
                            variant="primary"
                            icon="plus"
                            wire:click="openAddSource">
                            Add Source
                        </flux:button>

                    </div>

                    <flux:separator />

                    <div class="flex items-center justify-between gap-4">

                        <div class="w-full max-w-md">
                            <flux:input
                                wire:model.live.debounce.300ms="search"
                                placeholder="Search source of information..."
                                icon="magnifying-glass" />
                        </div>

                    </div>

                    {{-- TABLE --}}
                    <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">

                        <div class="overflow-x-auto">

                            <table class="w-full text-sm">

                                <thead class="bg-red-800 text-sm text-white">

                                    <tr class="border-b-2 border-white/30">

                                        <th class="px-4 py-3 text-left font-bold text-white">
                                            #
                                        </th>

                                        <th class="px-4 py-3 text-left font-bold text-white">
                                            Source Of Information
                                        </th>

                                        <th class="px-4 py-3 text-left font-bold text-white">
                                            Created
                                        </th>

                                        <th class="px-4 py-3 text-right font-bold text-white">
                                            Action
                                        </th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                                    @forelse($sourceOfInfos as $source)

                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">

                                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">
                                            {{ $sourceOfInfos->firstItem() + $loop->index }}
                                        </td>

                                        <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">
                                            {{ $source->source_name }}
                                        </td>

                                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">
                                            {{ $source->created_at
                                        ? \Carbon\Carbon::parse($source->created_at)->format('M d, Y')
                                        : '—' }}
                                        </td>

                                        <td class="px-4 py-3 text-right">

                                            <div class="flex justify-end gap-2">

                                                <flux:button
                                                    size="sm"
                                                    variant="ghost"
                                                    icon="pencil-square"
                                                    wire:click="editSource({{ $source->id }})">
                                                    Edit
                                                </flux:button>

                                                <flux:button
                                                    size="sm"
                                                    variant="danger"
                                                    icon="trash"
                                                    wire:click="confirmDeleteSource({{ $source->id }})">
                                                    Delete
                                                </flux:button>

                                            </div>

                                        </td>

                                    </tr>

                                    @empty

                                    <tr>

                                        <td
                                            colspan="4"
                                            class="px-4 py-10 text-center text-zinc-500 dark:text-zinc-400">

                                            No source of information found.

                                        </td>

                                    </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                    {{-- PAGINATION --}}
                    @if($sourceOfInfos->hasPages())
                    <div>
                        {{ $sourceOfInfos->links() }}
                    </div>
                    @endif

                </div>


                @elseif($resultTab === 'complainant')

                <div class="space-y-4">

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <flux:heading size="md">
                                Complainant Categories
                            </flux:heading>

                            <flux:subheading class="mt-1">
                                Manage complainant categories.
                            </flux:subheading>
                        </div>

                        <flux:button
                            variant="primary"
                            icon="plus"
                            wire:click="openAddComplainantCategory">
                            Add Category
                        </flux:button>

                    </div>

                    <flux:separator />

                    <div class="flex items-center justify-between gap-4">
                        <div class="w-full max-w-md">
                            <flux:input
                                wire:model.live.debounce.300ms="complainantSearch"
                                placeholder="Search complainant category..."
                                icon="magnifying-glass" />
                        </div>
                    </div>

                    <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">

                                <thead class="bg-red-800 text-sm text-white">

                                    <tr class="border-b-2 border-white/30">

                                        <th class="px-4 py-3 text-left font-bold text-white">
                                            #
                                        </th>

                                        <th class="px-4 py-3 text-left font-bold text-white">
                                            Complainant Category
                                        </th>

                                        <th class="px-4 py-3 text-left font-bold text-white">
                                            Created
                                        </th>

                                        <th class="px-4 py-3 text-right font-bold text-white">
                                            Action
                                        </th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                                    @forelse($complainantCategories as $category)

                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">

                                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">
                                            {{ $complainantCategories->firstItem() + $loop->index }}
                                        </td>

                                        <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">
                                            {{ $category->complain_name }}
                                        </td>

                                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">
                                            {{ $category->created_at
                                        ? \Carbon\Carbon::parse($category->created_at)->format('M d, Y')
                                        : '—' }}
                                        </td>

                                        <td class="px-4 py-3 text-right">

                                            <div class="flex justify-end gap-2">

                                                <flux:button
                                                    size="sm"
                                                    variant="ghost"
                                                    icon="pencil-square"
                                                    wire:click="editcomplain({{ $category->id }})">
                                                    Edit
                                                </flux:button>

                                                <flux:button
                                                    size="sm"
                                                    variant="danger"
                                                    icon="trash"
                                                    wire:click="confirmDeleteComplainantCategory({{ $category->id }})">
                                                    Delete
                                                </flux:button>

                                            </div>

                                        </td>

                                    </tr>

                                    @empty

                                    <tr>
                                        <td
                                            colspan="4"
                                            class="px-4 py-10 text-center text-zinc-500 dark:text-zinc-400">

                                            No complainant categories found.

                                        </td>
                                    </tr>

                                    @endforelse

                                </tbody>

                            </table>
                        </div>
                    </div>

                    @if($complainantCategories->hasPages())
                    <div>
                        {{ $complainantCategories->links() }}
                    </div>
                    @endif

                </div>

                @elseif($resultTab === 'data-error')

                <div class="space-y-4">

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <flux:heading size="md">
                                Data Information Error
                            </flux:heading>

                            <flux:subheading class="mt-1">
                                Configure Data Information Error settings.
                            </flux:subheading>
                        </div>

                        <flux:button
                            variant="primary"
                            icon="plus"
                            wire:click="openAddDataInformation">
                            Add Data Error
                        </flux:button>

                    </div>

                    <flux:separator />

                    <div class="flex items-center justify-between gap-4">
                        <div class="w-full max-w-md">
                            <flux:input
                                wire:model.live.debounce.300ms="dataInformationSearch"
                                placeholder="Search data information error..."
                                icon="magnifying-glass" />
                        </div>
                    </div>

                    <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">

                        <div class="overflow-x-auto">

                            <table class="w-full text-sm">

                                <thead class="bg-red-800 text-sm text-white">

                                    <tr class="border-b-2 border-white/30">

                                        <th class="px-4 py-3 text-left font-bold text-white">
                                            #
                                        </th>

                                        <th class="px-4 py-3 text-left font-bold text-white">
                                            Data Information Error
                                        </th>

                                        <th class="px-4 py-3 text-left font-bold text-white">
                                            Created
                                        </th>

                                        <th class="px-4 py-3 text-right font-bold text-white">
                                            Action
                                        </th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                                    @forelse($dataInformations as $dataInformation)

                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">

                                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">
                                            {{ $dataInformations->firstItem() + $loop->index }}
                                        </td>

                                        <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">
                                            {{ $dataInformation->data_name }}
                                        </td>

                                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">
                                            {{ $dataInformation->created_at
                                        ? \Carbon\Carbon::parse($dataInformation->created_at)->format('M d, Y')
                                        : '—' }}
                                        </td>

                                        <td class="px-4 py-3 text-right">

                                            <div class="flex justify-end gap-2">

                                                <flux:button
                                                    size="sm"
                                                    variant="ghost"
                                                    icon="pencil-square"
                                                    wire:click="editdata({{ $dataInformation->id }})">
                                                    Edit
                                                </flux:button>

                                                <flux:button
                                                    size="sm"
                                                    variant="danger"
                                                    icon="trash"
                                                    wire:click="deletedata({{ $dataInformation->id }})">
                                                    Delete
                                                </flux:button>

                                            </div>

                                        </td>

                                    </tr>

                                    @empty

                                    <tr>
                                        <td
                                            colspan="4"
                                            class="px-4 py-10 text-center text-zinc-500 dark:text-zinc-400">
                                            No data information errors found.
                                        </td>
                                    </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                    @if($dataInformations->hasPages())
                    <div>
                        {{ $dataInformations->links() }}
                    </div>
                    @endif

                </div>

                @elseif($resultTab === 'technical')

                <div class="space-y-4">

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <flux:heading size="md">
                                Technical Equipment Issues
                            </flux:heading>

                            <flux:subheading class="mt-1">
                                Configure Technical Equipment Issues settings.
                            </flux:subheading>
                        </div>
                        <flux:button
                            variant="primary"
                            icon="plus"
                            wire:click="openAddTechnicalEquipment">
                            Add Technical Equipment
                        </flux:button>
                    </div>

                    <flux:separator />

                    <div class="flex items-center justify-between gap-4">
                        <div class="w-full max-w-md">
                            <flux:input
                                wire:model.live.debounce.300ms="technicalSearch"
                                placeholder="Search technical equipment issue..."
                                icon="magnifying-glass" />
                        </div>
                    </div>

                    <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">

                        <div class="overflow-x-auto">

                            <table class="w-full text-sm">

                                <thead class="bg-red-800 text-sm text-white">

                                    <tr class="border-b-2 border-white/30">

                                        <th class="px-4 py-3 text-left font-bold text-white">
                                            #
                                        </th>

                                        <th class="px-4 py-3 text-left font-bold text-white">
                                            Technical Equipment Issue
                                        </th>

                                        <th class="px-4 py-3 text-left font-bold text-white">
                                            Created
                                        </th>

                                        <th class="px-4 py-3 text-right font-bold text-white">
                                            Action
                                        </th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                                    @forelse($technicalEquipments as $technicalEquipment)

                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">

                                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">
                                            {{ $technicalEquipments->firstItem() + $loop->index }}
                                        </td>

                                        <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">
                                            {{ $technicalEquipment->technical_name }}
                                        </td>

                                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">
                                            {{ $technicalEquipment->created_at
                                        ? \Carbon\Carbon::parse($technicalEquipment->created_at)->format('M d, Y')
                                        : '—' }}
                                        </td>

                                        <td class="px-4 py-3 text-right">

                                            <div class="flex justify-end gap-2">

                                                <flux:button
                                                    size="sm"
                                                    variant="ghost"
                                                    icon="pencil-square"
                                                    wire:click="edittechnical({{ $technicalEquipment->id }})">
                                                    Edit
                                                </flux:button>

                                                <flux:button
                                                    size="sm"
                                                    variant="danger"
                                                    icon="trash"
                                                    wire:click="deletetechnical({{ $technicalEquipment->id }})">
                                                    Delete
                                                </flux:button>

                                            </div>

                                        </td>

                                    </tr>

                                    @empty

                                    <tr>
                                        <td
                                            colspan="4"
                                            class="px-4 py-10 text-center text-zinc-500 dark:text-zinc-400">
                                            No technical equipment issues found.
                                        </td>
                                    </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                    @if($technicalEquipments->hasPages())
                    <div>
                        {{ $technicalEquipments->links() }}
                    </div>
                    @endif

                </div>

                @elseif($resultTab === 'quality')

                <div class="space-y-4">

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <flux:heading size="md">
                                Quality Accuracy Issues
                            </flux:heading>

                            <flux:subheading class="mt-1">
                                Configure Quality Accuracy Issues settings.
                            </flux:subheading>
                        </div>
                        <flux:button
                            variant="primary"
                            icon="plus"
                            wire:click="openAddQualityAccuracy">
                            Add Quality Accuracy
                        </flux:button>
                    </div>

                    <flux:separator />

                    <div class="flex items-center justify-between gap-4">
                        <div class="w-full max-w-md">
                            <flux:input
                                wire:model.live.debounce.300ms="qualitySearch"
                                placeholder="Search quality accuracy issue..."
                                icon="magnifying-glass" />
                        </div>
                    </div>

                    <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">

                        <div class="overflow-x-auto">

                            <table class="w-full text-sm">

                                <thead class="bg-red-800 text-sm text-white">
                                    <tr class="border-b-2 border-white/30">

                                        <th class="px-4 py-3 text-left font-bold text-white">
                                            #
                                        </th>

                                        <th class="px-4 py-3 text-left font-bold text-white">
                                            Quality Accuracy Issue
                                        </th>

                                        <th class="px-4 py-3 text-left font-bold text-white">
                                            Created
                                        </th>

                                        <th class="px-4 py-3 text-right font-bold text-white">
                                            Action
                                        </th>

                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                                    @forelse($qualityAccuracies as $qualityAccuracy)

                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">

                                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">
                                            {{ $qualityAccuracies->firstItem() + $loop->index }}
                                        </td>

                                        <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">
                                            {{ $qualityAccuracy->quality_name }}
                                        </td>

                                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">
                                            {{ $qualityAccuracy->created_at
                                        ? \Carbon\Carbon::parse($qualityAccuracy->created_at)->format('M d, Y')
                                        : '—' }}
                                        </td>

                                        <td class="px-4 py-3 text-right">

                                            <div class="flex justify-end gap-2">

                                                <flux:button
                                                    size="sm"
                                                    variant="ghost"
                                                    icon="pencil-square"
                                                    wire:click="editquality({{ $qualityAccuracy->id }})">
                                                    Edit
                                                </flux:button>

                                                <flux:button
                                                    size="sm"
                                                    variant="danger"
                                                    icon="trash"
                                                    wire:click="deletequality({{ $qualityAccuracy->id }})">
                                                    Delete
                                                </flux:button>

                                            </div>

                                        </td>

                                    </tr>

                                    @empty

                                    <tr>
                                        <td
                                            colspan="4"
                                            class="px-4 py-10 text-center text-zinc-500 dark:text-zinc-400">
                                            No quality accuracy issues found.
                                        </td>
                                    </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                    @if($qualityAccuracies->hasPages())
                    <div>
                        {{ $qualityAccuracies->links() }}
                    </div>
                    @endif

                </div>

                @endif

            </div>

        </div>

    </div>

    <!-- source information -->
    <flux:modal name="add-source" class="md:w-[500px]">
        <div class="space-y-6">

            <div>
                <flux:heading size="lg">
                    {{ $editingSourceId ? 'Edit Source Of Information' : 'Add Source Of Information' }}
                </flux:heading>

                <flux:subheading class="mt-1">
                    {{ $editingSourceId
                    ? 'Update the source of information.'
                    : 'Add a new source of information.' }}
                </flux:subheading>
            </div>

            <flux:input
                wire:model="sourceName"
                label="Source Of Information"
                placeholder="Enter source of information"
                class="uppercase"
                autofocus />

            <div class="flex justify-end gap-2">

                <flux:modal.close>
                    <flux:button
                        variant="ghost"
                        wire:click="resetSourceForm">
                        Cancel
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    variant="primary"
                    wire:click="saveSource"
                    wire:loading.attr="disabled">

                    <span wire:loading.remove wire:target="saveSource">
                        {{ $editingSourceId ? 'Update Source' : 'Save Source' }}
                    </span>

                    <span wire:loading wire:target="saveSource">
                        {{ $editingSourceId ? 'Updating...' : 'Saving...' }}
                    </span>

                </flux:button>

            </div>

        </div>
    </flux:modal>

    <!-- complainant category -->
    <flux:modal name="add-complainant-category" class="md:w-[500px]">
        <div class="space-y-6">

            <div>
                <flux:heading size="lg">
                    {{ $editingComplainantCategoryId
                    ? 'Edit Complainant Category'
                    : 'Add Complainant Category' }}
                </flux:heading>

                <flux:subheading class="mt-1">
                    {{ $editingComplainantCategoryId
                    ? 'Update the complainant category.'
                    : 'Add a new complainant category.' }}
                </flux:subheading>
            </div>

            <flux:input
                wire:model="complainantCategoryName"
                label="Complainant Category"
                placeholder="Enter complainant category"
                class="uppercase"
                autofocus />

            <div class="flex justify-end gap-2">

                <flux:modal.close>
                    <flux:button
                        variant="ghost"
                        wire:click="resetComplainantCategoryForm">
                        Cancel
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    variant="primary"
                    wire:click="saveComplainantCategory"
                    wire:loading.attr="disabled">

                    <span
                        wire:loading.remove
                        wire:target="saveComplainantCategory">
                        {{ $editingComplainantCategoryId
                        ? 'Update Category'
                        : 'Save Category' }}
                    </span>

                    <span
                        wire:loading
                        wire:target="saveComplainantCategory">
                        {{ $editingComplainantCategoryId
                        ? 'Updating...'
                        : 'Saving...' }}
                    </span>

                </flux:button>

            </div>

        </div>
    </flux:modal>

    <!-- data information error -->
    <flux:modal name="add-data-information-error" class="md:w-[500px]">
        <div class="space-y-6">

            <div>
                <flux:heading size="lg">
                    Add Data Information Error
                </flux:heading>
            </div>

            <flux:input
                wire:model="dataInformationName"
                label="Data Information"
                placeholder="Enter data information"
                autofocus />

            <div class="flex justify-end gap-2">

                <flux:modal.close>
                    <flux:button variant="ghost">
                        Cancel
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    variant="primary"
                    wire:click="saveDataInformationError"
                    wire:loading.attr="disabled">

                    <span wire:loading.remove wire:target="saveDataInformationError">
                        Save Error
                    </span>

                    <span wire:loading wire:target="saveDataInformationError">
                        Saving...
                    </span>

                </flux:button>

            </div>

        </div>
    </flux:modal>

    <!-- technical Equipment Issues -->
    <flux:modal name="add-technical-equipment" class="md:w-[500px]">
        <div class="space-y-6">

            <div>
                <flux:heading size="lg">
                    Add Technical Equipment Issue
                </flux:heading>
            </div>

            <flux:input
                wire:model="technicalName"
                label="Technical Equipment"
                placeholder="Enter technical equipment"
                autofocus />

            <div class="flex justify-end gap-2">

                <flux:modal.close>
                    <flux:button variant="ghost">
                        Cancel
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    variant="primary"
                    wire:click="saveTechnicalEquipment"
                    wire:loading.attr="disabled">

                    <span wire:loading.remove wire:target="saveTechnicalEquipment">
                        Save Issue
                    </span>

                    <span wire:loading wire:target="saveTechnicalEquipment">
                        Saving...
                    </span>

                </flux:button>

            </div>

        </div>
    </flux:modal>

    <!-- quality Accuracy Issues -->
    <flux:modal name="add-quality-accuracy-issue" class="md:w-[500px]">
        <div class="space-y-6">

            <div>
                <flux:heading size="lg">
                    Add Quality Accuracy Issue
                </flux:heading>
            </div>

            <flux:input
                wire:model="qualityName"
                label="Quality Accuracy"
                placeholder="Enter quality accuracy"
                autofocus />

            <div class="flex justify-end gap-2">

                <flux:modal.close>
                    <flux:button variant="ghost">
                        Cancel
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    variant="primary"
                    wire:click="saveQualityAccuracy"
                    wire:loading.attr="disabled">

                    <span wire:loading.remove wire:target="saveQualityAccuracy">
                        Save Issue
                    </span>

                    <span wire:loading wire:target="saveQualityAccuracy">
                        Saving...
                    </span>

                </flux:button>

            </div>

        </div>
    </flux:modal>

    <!-- delete source information  -->
    <flux:modal name="delete-source" class="md:w-[450px]">
        <div class="space-y-6">

            <div>
                <flux:heading size="lg">
                    Delete Source Of Information
                </flux:heading>

                <flux:subheading class="mt-1">
                    Are you sure you want to delete this source?
                </flux:subheading>
            </div>

            <div class="rounded-lg bg-red-50 p-4 dark:bg-red-950/30">
                <p class="text-sm text-red-700 dark:text-red-300">
                    <span class="font-semibold">
                        {{ $deleteSourceName }}
                    </span>
                    will be permanently deleted.
                </p>
            </div>

            <div class="flex justify-end gap-2">

                <flux:modal.close>
                    <flux:button variant="ghost">
                        Cancel
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    variant="danger"
                    wire:click="deleteSource"
                    wire:loading.attr="disabled">

                    <span wire:loading.remove wire:target="deleteSource">
                        Delete
                    </span>

                    <span wire:loading wire:target="deleteSource">
                        Deleting...
                    </span>

                </flux:button>

            </div>

        </div>
    </flux:modal>

    <!-- delete complainant category  -->
    <flux:modal
        name="delete-complainant-category"
        class="md:w-[450px]">

        <div class="space-y-6">

            <div>
                <flux:heading size="lg">
                    Delete Complainant Category
                </flux:heading>

                <flux:subheading class="mt-1">
                    Are you sure you want to delete this category?
                </flux:subheading>
            </div>

            <div class="rounded-lg bg-red-50 p-4 dark:bg-red-950/30">
                <p class="text-sm text-red-700 dark:text-red-300">
                    <span class="font-semibold">
                        {{ $deleteComplainantCategoryName }}
                    </span>
                    will be permanently deleted.
                </p>
            </div>

            <div class="flex justify-end gap-2">

                <flux:modal.close>
                    <flux:button variant="ghost">
                        Cancel
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    variant="danger"
                    wire:click="deleteComplainantCategory"
                    wire:loading.attr="disabled">

                    <span
                        wire:loading.remove
                        wire:target="deleteComplainantCategory">
                        Delete
                    </span>

                    <span
                        wire:loading
                        wire:target="deleteComplainantCategory">
                        Deleting...
                    </span>

                </flux:button>

            </div>

        </div>

    </flux:modal>

    <!-- delete data information error -->
    <flux:modal name="delete-data-information-error" class="md:w-[450px]">
        <div class="space-y-6">

            <div>
                <flux:heading size="lg">
                    Delete Data Information Error
                </flux:heading>

                <flux:subheading class="mt-1">
                    Are you sure you want to delete this data information error?
                </flux:subheading>
            </div>

            <div class="rounded-lg bg-red-50 p-4 dark:bg-red-950/30">
                <p class="text-sm text-red-700 dark:text-red-300">
                    <span class="font-semibold">
                        {{ $deleteDataInformationName }}
                    </span>
                    will be permanently deleted.
                </p>
            </div>

            <div class="flex justify-end gap-2">

                <flux:modal.close>
                    <flux:button variant="ghost">
                        Cancel
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    variant="danger"
                    wire:click="deleteDataInformationError"
                    wire:loading.attr="disabled">

                    <span wire:loading.remove wire:target="deleteDataInformationError">
                        Delete
                    </span>

                    <span wire:loading wire:target="deleteDataInformationError">
                        Deleting...
                    </span>

                </flux:button>

            </div>

        </div>
    </flux:modal>

    <!-- delete technical equipment issue -->
    <flux:modal name="delete-technical-equipment" class="md:w-[450px]">
        <div class="space-y-6">

            <div>
                <flux:heading size="lg">
                    Delete Technical Equipment Issue
                </flux:heading>

                <flux:subheading class="mt-1">
                    Are you sure you want to delete this technical equipment issue?
                </flux:subheading>
            </div>

            <div class="rounded-lg bg-red-50 p-4 dark:bg-red-950/30">
                <p class="text-sm text-red-700 dark:text-red-300">
                    <span class="font-semibold">
                        {{ $deleteTechnicalEquipmentName }}
                    </span>
                    will be permanently deleted.
                </p>
            </div>

            <div class="flex justify-end gap-2">

                <flux:modal.close>
                    <flux:button variant="ghost">
                        Cancel
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    variant="danger"
                    wire:click="deleteTechnicalEquipment"
                    wire:loading.attr="disabled">

                    <span wire:loading.remove wire:target="deleteTechnicalEquipment">
                        Delete
                    </span>

                    <span wire:loading wire:target="deleteTechnicalEquipment">
                        Deleting...
                    </span>

                </flux:button>

            </div>

        </div>
    </flux:modal>

    <!-- delete quality Accuracy Issues -->
    <flux:modal name="delete-quality-accuracy" class="md:w-[450px]">
        <div class="space-y-6">

            <div>
                <flux:heading size="lg">
                    Delete Quality Accuracy Issue
                </flux:heading>

                <flux:subheading class="mt-1">
                    Are you sure you want to delete this quality accuracy issue?
                </flux:subheading>
            </div>

            <div class="rounded-lg bg-red-50 p-4 dark:bg-red-950/30">
                <p class="text-sm text-red-700 dark:text-red-300">
                    <span class="font-semibold">
                        {{ $deleteQualityAccuracyName }}
                    </span>
                    will be permanently deleted.
                </p>
            </div>

            <div class="flex justify-end gap-2">

                <flux:modal.close>
                    <flux:button variant="ghost">
                        Cancel
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    variant="danger"
                    wire:click="deleteQualityAccuracy"
                    wire:loading.attr="disabled">

                    <span wire:loading.remove wire:target="deleteQualityAccuracy">
                        Delete
                    </span>

                    <span wire:loading wire:target="deleteQualityAccuracy">
                        Deleting...
                    </span>

                </flux:button>

            </div>

        </div>
    </flux:modal>

</div>