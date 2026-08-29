<div>
    <div class="mb-4 rounded-xl border border-zinc-200 dark:border-zinc-700
            bg-white dark:bg-zinc-900 p-4">
        <div class="flex flex-col gap-4 md:flex-row md:items-end">
            <div class="flex-1">
                <flux:input
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search CPAR No., reported by, employee..."
                    icon="magnifying-glass" />
            </div>
            <div class="w-full md:w-64">
                <flux:select
                    wire:model.live="statusFilter"
                    label="Status">
                    <flux:select.option value="ALL">
                        All Status
                    </flux:select.option>
                    @foreach($statuses as $status)
                    <flux:select.option value="{{ $status->id }}">
                        {{ $status->status_name }}
                    </flux:select.option>
                    @endforeach
                </flux:select>
            </div>
        </div>
    </div>
    <div class="w-full overflow-x-auto">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-zinc-200 dark:border-zinc-700">
                        <th class="px-4 py-3 text-left">
                            CPAR No.
                        </th>
                        <th class="px-4 py-3 text-left">
                            Reported By
                        </th>
                        <td class="px-4 py-3">
                            <div class="font-medium">
                                @if(!empty(trim($cpar->employee_name ?? '')))
                                {{ $cpar->employee_name }}
                                @elseif(!empty(trim($cpar->dept_employee_name ?? '')))
                                {{ $cpar->dept_employee_name }}
                                <div class="text-xs text-zinc-500">
                                    Reported Dept Head
                                </div>
                                @else
                                    Dept Head Reported
                                @endif
                            </div>
                        </td>
                        <th class="px-4 py-3 text-left">
                            Status
                        </th>
                        <th class="px-4 py-3 text-center">
                            Status Tracker
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cpar_offense as $cpar)
                    <tr class="border-b border-zinc-100 dark:border-zinc-800">
                        <td class="px-4 py-3 font-medium">
                            {{ $cpar->cpar_no }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $cpar->reported_by }}
                            @if($cpar->employee_no)
                            <div class="text-xs text-zinc-500">
                                {{ $cpar->employee_no }}
                            </div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium">
                                {{ $cpar->employee_name ?: ($cpar->dept_employee_name ?: 'Unassigned') }}
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full bg-zinc-100 px-3 py-1 text-xs font-semibold text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                                {{ $cpar->status_name ?? 'No Status' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php
                            $tracker = match ((int) $cpar->status_id) {
                            1 => ['label' => 'Department Head', 'color' => 'blue'],
                            5 => ['label' => 'HR Head', 'color' => 'purple'],
                            10 => ['label' => 'Reported Employee', 'color' => 'orange'],
                            15 => ['label' => 'Department Head', 'color' => 'blue'],
                            20 => ['label' => 'HR Head', 'color' => 'purple'],
                            23 => ['label' => 'Reported Employee', 'color' => 'orange'],
                            25 => ['label' => 'HR Head', 'color' => 'purple'],
                            30 => ['label' => 'HR Head', 'color' => 'purple'],
                            35 => ['label' => 'LAB Supervisor', 'color' => 'green'],
                            40 => ['label' => 'HR Head', 'color' => 'purple'],
                            50, 55 => ['label' => 'Done', 'color' => 'green'],
                            default => ['label' => 'No Tracker', 'color' => 'gray'],
                            };
                            @endphp
                            <span
                                class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                                {{ match($tracker['color']) {
                                    'blue'   => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
                                    'purple' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300',
                                    'orange' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300',
                                    'green'  => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
                                    default  => 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-300',
                                } }}">
                                {{ $tracker['label'] }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td
                            colspan="5"
                            class="px-4 py-8 text-center text-zinc-500">
                            No CPAR records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>