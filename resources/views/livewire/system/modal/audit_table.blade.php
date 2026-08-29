<div class="bg-white dark:bg-gray-900">

    {{-- SEARCH --}}
    <div class="mb-4">

        <div class="w-full max-w-md">

            <flux:input
                wire:model.live.debounce.300ms="search"
                placeholder="Search audit logs..."
                icon="magnifying-glass"
                class="border-0 shadow-none ring-0 focus:ring-0"
            />

        </div>

    </div>


    {{-- TABLE --}}
    <div class="overflow-x-auto">

        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                <tr>

                    <th class="whitespace-nowrap px-6 py-3">
                        User Reported By
                    </th>

                    <th class="whitespace-nowrap px-6 py-3">
                        User Reported
                    </th>

                    <th class="whitespace-nowrap px-6 py-3">
                        Action
                    </th>

                    <th class="min-w-[280px] px-6 py-3">
                        Old Value
                    </th>

                    <th class="min-w-[280px] px-6 py-3">
                        New Value
                    </th>

                    <th class="whitespace-nowrap px-6 py-3">
                        Status Changed By
                    </th>

                    <th class="whitespace-nowrap px-6 py-3">
                        Date
                    </th>

                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($auditLogs as $log)
                    <tr
                        wire:key="audit-log-{{ $log->id }}"
                        class="hover:bg-gray-50 dark:hover:bg-gray-800"
                    >

                        {{-- USER REPORTED BY --}}
                        <td class="whitespace-nowrap px-6 py-4 font-medium text-gray-900 dark:text-white">
                            {{ $log->user_reported_by ?? '-' }}
                        </td>


                        {{-- USER REPORTED --}}
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">

                            @php
                                $reportedUsers = json_decode(
                                    $log->reported_employee ?? '',
                                    true
                                );
                            @endphp

                            @if (is_array($reportedUsers))

                                <div class="flex flex-wrap gap-1">

                                    @foreach ($reportedUsers as $user)

                                        <span class="rounded-md bg-gray-100 px-2 py-1 text-xs dark:bg-gray-700">
                                            {{ $user ?? '-' }}
                                        </span>

                                    @endforeach

                                </div>

                            @else

                                {{ $log->reported_employee ?? '-' }}

                            @endif

                        </td>


                        {{-- ACTION --}}
                        <td class="whitespace-nowrap px-6 py-4">

                            @php
                                $actionClass = match ($log->action) {

                                    'CREATED' =>
                                        'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',

                                    'ASSIGNED' =>
                                        'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',

                                    'RE-ASSIGNED' =>
                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',

                                    'SUBMITTED' =>
                                        'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300',

                                    'ACKNOWLEDGED' =>
                                        'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300',

                                    'CPAR RESPONSE SUBMITTED' =>
                                        'bg-cyan-100 text-cyan-800 dark:bg-cyan-900/30 dark:text-cyan-300',

                                    default =>
                                        'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                };
                            @endphp

                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ $actionClass }}">
                                {{ $log->action ?? '-' }}
                            </span>

                        </td>


                        {{-- OLD VALUE --}}
                        <td class="max-w-md px-6 py-4">

                            @php
                                $oldValue = json_decode(
                                    $log->old_value ?? '',
                                    true
                                );
                            @endphp

                            @if (is_array($oldValue))

                                <div class="space-y-1">

                                    @foreach ($oldValue as $key => $value)

                                        <div class="text-xs">

                                            <span class="font-semibold text-gray-600 dark:text-gray-400">
                                                {{ ucwords(str_replace('_', ' ', $key)) }}:
                                            </span>

                                            <span class="break-words text-gray-700 dark:text-gray-300">

                                                @if (is_array($value))

                                                    {{ json_encode($value, JSON_UNESCAPED_UNICODE) }}

                                                @elseif ($value !== null && $value !== '')

                                                    {{ $value }}

                                                @else

                                                    -

                                                @endif

                                            </span>

                                        </div>

                                    @endforeach

                                </div>

                            @else

                                <span class="text-gray-500 dark:text-gray-400">
                                    -
                                </span>

                            @endif

                        </td>


                        {{-- NEW VALUE --}}
                        <td class="max-w-md px-6 py-4">

                            @php
                                $newValue = json_decode(
                                    $log->new_value ?? '',
                                    true
                                );
                            @endphp

                            @if (is_array($newValue))

                                <div class="space-y-1">

                                    @foreach ($newValue as $key => $value)

                                        <div class="text-xs">

                                            <span class="font-semibold text-gray-600 dark:text-gray-400">
                                                {{ ucwords(str_replace('_', ' ', $key)) }}:
                                            </span>

                                            <span class="break-words text-gray-700 dark:text-gray-300">

                                                @if (is_array($value))

                                                    {{ json_encode($value, JSON_UNESCAPED_UNICODE) }}

                                                @elseif ($value !== null && $value !== '')

                                                    {{ $value }}

                                                @else

                                                    -

                                                @endif

                                            </span>

                                        </div>

                                    @endforeach

                                </div>

                            @else

                                <span class="text-gray-500 dark:text-gray-400">
                                    -
                                </span>

                            @endif

                        </td>


                        {{-- STATUS CHANGED BY --}}
                        <td class="whitespace-nowrap px-6 py-4 text-gray-700 dark:text-gray-300">
                            {{ $log->status_changed_employee ?? '-' }}
                        </td>


                        {{-- DATE --}}
                        <td class="whitespace-nowrap px-6 py-4 text-gray-600 dark:text-gray-400">

                            @if ($log->date)

                                {{ \Carbon\Carbon::parse($log->date)->format('M d, Y h:i A') }}

                            @else

                                -

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="8"
                            class="px-6 py-12 text-center text-gray-500 dark:text-gray-400"
                        >
                            No audit logs found.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- PAGINATION --}}
    <div class="mt-4">

        {{ $auditLogs->links() }}

    </div>

</div>