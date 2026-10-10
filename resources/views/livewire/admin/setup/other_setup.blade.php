<div class="space-y-6">

    <div>
        <flux:heading size="lg">
            Others Setup
        </flux:heading>

        <flux:subheading>
            Manage other system configurations.
        </flux:subheading>
    </div>

    <flux:separator />

    @include('toast')

    <div class="grid grid-cols-12 gap-6">

        <div class="col-span-12 md:col-span-4">

            <div class="rounded-xl border border-zinc-200 bg-white p-2 dark:border-zinc-700 dark:bg-zinc-900">

                <flux:navlist>

                    <flux:navlist.item
                        wire:click="$set('tab', 'complainant')"
                        icon="user-group"
                        :current="$tab === 'complainant'"
                        >

                        Complainant Category

                    </flux:navlist.item>

                    <flux:navlist.item
                        wire:click="$set('tab', 'concern')"
                        icon="exclamation-triangle"
                        :current="$tab === 'concern'"
                        >

                        Concern Category

                    </flux:navlist.item>

                    <flux:navlist.item
                        wire:click="$set('tab', 'priority')"
                        icon="flag"
                        :current="$tab === 'priority'"
                        >

                        Priority

                    </flux:navlist.item>

                </flux:navlist>

            </div>

        </div>

        <div class="col-span-12 md:col-span-8">

            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">

                @if($tab === 'complainant')
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
                @elseif($tab === 'concern')
                <div class="space-y-4">

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                        <div>
                            <flux:heading size="md">
                                Concern Categories
                            </flux:heading>

                            <flux:subheading class="mt-1">
                                Manage concern categories.
                            </flux:subheading>
                        </div>

                        <flux:button
                            variant="primary"
                            icon="plus"
                            wire:click="openAddConcernCategory">

                            Add Concern

                        </flux:button>

                    </div>

                    <flux:separator />

                    <div class="flex items-center justify-between gap-4">

                        <div class="w-full max-w-md">

                            <flux:input
                                wire:model.live.debounce.300ms="concernSearch"
                                placeholder="Search concern category..."
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
                                            Concern Category
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

                                    @forelse($concernCategories as $concern)

                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">

                                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">
                                            {{ $concernCategories->firstItem() + $loop->index }}
                                        </td>

                                        <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">
                                            {{ $concern->concern_name }}
                                        </td>

                                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">
                                            {{ $concern->created_at
                                                        ? \Carbon\Carbon::parse($concern->created_at)->format('M d, Y')
                                                        : '—' }}
                                        </td>

                                        <td class="px-4 py-3 text-right">

                                            <div class="flex justify-end gap-2">

                                                <flux:button
                                                    size="sm"
                                                    variant="ghost"
                                                    icon="pencil-square"
                                                    wire:click="editConcern({{ $concern->id }})">

                                                    Edit

                                                </flux:button>

                                                <flux:button
                                                    size="sm"
                                                    variant="danger"
                                                    icon="trash"
                                                    wire:click="confirmDeleteConcern({{ $concern->id }})">

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

                                            No concern categories found.

                                        </td>

                                    </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                    @if($concernCategories->hasPages())

                    <div>
                        {{ $concernCategories->links() }}
                    </div>

                    @endif

                </div>
                @elseif($tab === 'priority')
                <div class="space-y-4">

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                        <div>
                            <flux:heading size="md">
                                Priority Levels
                            </flux:heading>

                            <flux:subheading class="mt-1">
                                Manage priority levels.
                            </flux:subheading>
                        </div>

                        <flux:button
                            variant="primary"
                            icon="plus"
                            wire:click="openAddPriority">
                            Add Priority
                        </flux:button>

                    </div>

                    <flux:separator />

                    <div class="flex items-center justify-between gap-4">

                        <div class="w-full max-w-md">

                            <flux:input
                                wire:model.live.debounce.300ms="prioritySearch"
                                placeholder="Search priority..."
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
                                            Priority
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

                                    @forelse($priorities as $priority)

                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">

                                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">
                                            {{ $priorities->firstItem() + $loop->index }}
                                        </td>

                                        <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">
                                            {{ $priority->priority_name }}
                                        </td>

                                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">
                                            {{ $priority->created_at
                                                        ? \Carbon\Carbon::parse($priority->created_at)->format('M d, Y')
                                                        : '—' }}
                                        </td>

                                        <td class="px-4 py-3 text-right">

                                            <div class="flex justify-end gap-2">
                                                <flux:button
                                                    size="sm"
                                                    variant="ghost"
                                                    icon="pencil-square"
                                                    wire:click="editPriority({{ $priority->id }})">
                                                    Edit
                                                </flux:button>

                                                <flux:button
                                                    size="sm"
                                                    variant="danger"
                                                    icon="trash"
                                                    wire:click="confirmDeletePriority({{ $priority->id }})">
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

                                            No priority levels found.

                                        </td>

                                    </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                    @if($priorities->hasPages())

                    <div>
                        {{ $priorities->links() }}
                    </div>

                    @endif

                </div>
                @endif

            </div>

        </div>

    </div>

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
                wire:model.live="complainantCategoryName"
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

    <!-- delete complainant category -->
    <flux:modal name="delete-complainant-category" class="md:w-[450px]">

        <div class="space-y-6">

            <div>
                <flux:heading size="lg">
                    Delete Complainant Category
                </flux:heading>

                <flux:subheading class="mt-1">
                    Are you sure you want to delete this complainant category?
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

    <!-- concern category -->
    <flux:modal name="add-concern-category" class="md:w-[500px]">

        <div class="space-y-6">

            <div>
                <flux:heading size="lg">
                    {{ $editingConcernCategoryId
                    ? 'Edit Concern Category'
                    : 'Add Concern Category' }}
                </flux:heading>

                <flux:subheading class="mt-1">
                    {{ $editingConcernCategoryId
                    ? 'Update the concern category.'
                    : 'Add a new concern category.' }}
                </flux:subheading>
            </div>

            <flux:input
                wire:model.live="concernCategoryName"
                label="Concern Category"
                placeholder="Enter concern category"
                class="uppercase"
                autofocus />

            <div class="flex justify-end gap-2">

                <flux:modal.close>
                    <flux:button
                        variant="ghost"
                        wire:click="resetConcernCategoryForm">
                        Cancel
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    variant="primary"
                    wire:click="saveConcernCategory"
                    wire:loading.attr="disabled">

                    <span
                        wire:loading.remove
                        wire:target="saveConcernCategory">
                        {{ $editingConcernCategoryId
                        ? 'Update Category'
                        : 'Save Category' }}
                    </span>

                    <span
                        wire:loading
                        wire:target="saveConcernCategory">
                        {{ $editingConcernCategoryId
                        ? 'Updating...'
                        : 'Saving...' }}
                    </span>

                </flux:button>

            </div>

        </div>

    </flux:modal>

    <!-- delete concern category -->
    <flux:modal name="delete-concern-category" class="md:w-[450px]">

        <div class="space-y-6">

            <div>
                <flux:heading size="lg">
                    Delete Concern Category
                </flux:heading>

                <flux:subheading class="mt-1">
                    Are you sure you want to delete this concern category?
                </flux:subheading>
            </div>

            <div class="rounded-lg bg-red-50 p-4 dark:bg-red-950/30">

                <p class="text-sm text-red-700 dark:text-red-300">

                    <span class="font-semibold">
                        {{ $deleteConcernCategoryName }}
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
                    wire:click="deleteConcern"
                    wire:loading.attr="disabled">

                    <span
                        wire:loading.remove
                        wire:target="deleteConcern">
                        Delete
                    </span>

                    <span
                        wire:loading
                        wire:target="deleteConcern">
                        Deleting...
                    </span>

                </flux:button>

            </div>

        </div>

    </flux:modal>

    <!-- priority levels -->
    <flux:modal name="add-priority" class="md:w-[500px]">

        <div class="space-y-6">

            <div>
                <flux:heading size="lg">
                    {{ $editingPriorityId
                    ? 'Edit Priority'
                    : 'Add Priority' }}
                </flux:heading>

                <flux:subheading class="mt-1">
                    {{ $editingPriorityId
                    ? 'Update the priority.'
                    : 'Add a new priority.' }}
                </flux:subheading>
            </div>

            <flux:input
                wire:model.live="priorityName"
                label="Priority"
                placeholder="Enter priority"
                class="uppercase"
                autofocus />

            <div class="flex justify-end gap-2">

                <flux:modal.close>
                    <flux:button
                        variant="ghost"
                        wire:click="resetPriorityForm">
                        Cancel
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    variant="primary"
                    wire:click="savePriority"
                    wire:loading.attr="disabled">

                    <span
                        wire:loading.remove
                        wire:target="savePriority">
                        {{ $editingPriorityId
                        ? 'Update Priority'
                        : 'Save Priority' }}
                    </span>

                    <span
                        wire:loading
                        wire:target="savePriority">
                        {{ $editingPriorityId
                        ? 'Updating...'
                        : 'Saving...' }}
                    </span>

                </flux:button>

            </div>

        </div>

    </flux:modal>

    <!-- delete priority levels -->
    <flux:modal name="delete-priority" class="md:w-[450px]">

        <div class="space-y-6">

            <div>
                <flux:heading size="lg">
                    Delete Priority
                </flux:heading>

                <flux:subheading class="mt-1">
                    Are you sure you want to delete this priority?
                </flux:subheading>
            </div>

            <div class="rounded-lg bg-red-50 p-4 dark:bg-red-950/30">

                <p class="text-sm text-red-700 dark:text-red-300">

                    <span class="font-semibold">
                        {{ $deletePriorityName }}
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
                    wire:click="deletePriority"
                    wire:loading.attr="disabled">

                    <span
                        wire:loading.remove
                        wire:target="deletePriority">
                        Delete
                    </span>

                    <span
                        wire:loading
                        wire:target="deletePriority">
                        Deleting...
                    </span>

                </flux:button>

            </div>

        </div>

    </flux:modal>
</div>