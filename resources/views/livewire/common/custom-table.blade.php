<div class="p-6">

    {{-- Toolbar --}}
    <div class="flex justify-between items-center gap-3 mb-4">

        {{-- LEFT SIDE --}}
        <div class="flex items-center gap-3">

            {{-- Search --}}
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search..."
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-64">

            {{-- Per Page --}}
            <select
                wire:model.live="perPage"
                class="border rounded-lg px-3 py-2">

                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
                <option value="100">100 per page</option>

            </select>

            {{-- Refresh --}}
            <flux:tooltip content="Refresh data">
                <flux:button
                    variant="outline"
                    wire:click="$dispatch('refresh')"
                    wire:loading.attr="disabled">

                    <svg
                        wire:loading.remove
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-5">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />

                    </svg>

                    <svg
                        wire:loading
                        class="animate-spin size-5"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24">

                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4" />

                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8v8H4z" />

                    </svg>

                </flux:button>
            </flux:tooltip>

            {{-- Columns --}}
            <flux:dropdown>

                <flux:button icon:trailing="chevron-down">
                    Columns
                </flux:button>

                <flux:menu>

                    @foreach($columns as $column => $label)

                    <flux:menu.checkbox
                        :checked="$visibleColumns[$column]"
                        wire:click="toggleColumnVisibility('{{ $column }}')">

                        {{ $label }}

                    </flux:menu.checkbox>

                    @endforeach

                </flux:menu>

            </flux:dropdown>
        </div>

        {{-- RIGHT SIDE --}}
        @if($addRoute)

        <flux:button
            variant="primary"
            wire:click="$dispatch('open-modal', { name: '{{ $addRoute }}' })">

            + Add {{ $addLabel }}

        </flux:button>

        @endif

    </div>

    {{-- TABLE --}}
    <div class="overflow-x-auto rounded-lg border">

        <table class="w-full text-sm text-center">

            <thead class="bg-red-800 text-white">

                <tr>

                    @foreach($columns as $field => $label)

                    @if($visibleColumns[$field])

                    <th class="border px-3 py-2">

                        {{ $label }}

                    </th>

                    @endif

                    @endforeach

                </tr>

            </thead>

            <tbody>

                @forelse($records as $record)

                <tr wire:key="user-row-{{ $record->id }}">

                    @foreach($columns as $field => $label)

                    @if($visibleColumns[$field])

                    <td class="border px-3 py-2">

                        @switch($field)

                        @case('status')

                        <span class="px-2 py-1 rounded-full text-xs text-white
                                                {{ strtolower($record->status) == 'active'
                                                    ? 'bg-green-600'
                                                    : 'bg-red-600' }}">

                            {{ $record->status }}

                        </span>

                        @break

                        @case('actions')

                        <div class="flex justify-center gap-2">

                            @if($model == 'App\Models\User')

                            <flux:button
                                size="sm"
                                variant="primary"
                                class="bg-blue-600 text-white hover:bg-blue-700"
                                icon="pencil"
                                class="bg-blue-600 text-white"
                                wire:click="editRecord({{ $record->id }})">
                                Edit
                            </flux:button>

                            <flux:button
                                size="sm"
                                variant="primary"
                                class="bg-green-600 text-white hover:bg-green-700"
                                icon="shield-check"
                                wire:click="permissionRecord({{ $record->id }})">
                                Permission
                            </flux:button>

                            @elseif($model == 'Spatie\Permission\Models\Role')

                            <flux:button
                                size="sm"
                                icon="pencil"
                                variant="primary"
                                class="bg-blue-600 text-white hover:bg-blue-700"
                                wire:click="editRole({{ $record->id }})">
                                Edit
                            </flux:button>

                            @endif

                        </div>

                        @break

                        @default

                        {{ data_get($record, $field) }}

                        @endswitch

                    </td>

                    @endif

                    @endforeach

                </tr>

                @empty

                <tr>

                    <td colspan="{{ count($columns) }}"
                        class="py-4 text-center">

                        No data found.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-4">

        <div wire:key="pagination-{{ $model }}">
            {{ $records->links() }}
        </div>

    </div>

</div>