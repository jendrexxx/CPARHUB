<div>
    <flux:modal name="CPARModal" class="w-[120%] max-w-[1500px] mt-6 top-0 z-50">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">CPAR Request</flux:heading>
                <flux:text>Below is the list of your filed CPAR requests.</flux:text>
            </div>

            <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700 text-center">
                <div class="w-full overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
                    <table class="w-full text-sm text-center">
                        <thead class="bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 uppercase text-xs tracking-wider">
                            <tr>
                                <th class="px-4 py-3">CPAR No.</th>
                                <th class="px-4 py-3">Reported By</th>
                                <th class="px-4 py-3">Department Name</th>
                                <th class="px-4 py-3">Date Open</th>
                                <th class="px-4 py-3">Assigned Employee</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @forelse ($cpar_requests as $request)
                            <tr wire:key="cpar-{{ $request->id }}">

                                <td class="px-4 py-3 font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $request->cpar_no }}
                                </td>

                                <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300">
                                    {{ $request->reported_by }}
                                </td>

                                <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300">
                                    {{ $request->department_name }}
                                </td>

                                <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300">
                                    {{ \Carbon\Carbon::parse($request->date_open)->format('M d, Y') }}
                                </td>

                                <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300">
                                    {{ $request->dept_head_name }}
                                </td>

                                <td class="px-4 py-3">
                                    @if($request->status_name == 'PENDING')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        {{ $request->status_name }}
                                    </span>
                                    @elseif($request->status_name == 'APPROVED')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        {{ $request->status_name }}
                                    </span>
                                    @elseif($request->status_name == 'CLOSED')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $request->status_name }}
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-zinc-100 text-zinc-800 dark:bg-zinc-700 dark:text-zinc-300">
                                        {{ $request->status_name }}
                                    </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <flux:dropdown align="end">
                                        <flux:button
                                            size="sm"
                                            variant="ghost"
                                            icon="ellipsis-vertical">
                                        </flux:button>
                                        <flux:menu>
                                            {{-- View Details --}}
                                            <flux:menu.item
                                                icon="eye"
                                                wire:click="viewDetails({{ $request->id }})">
                                                View Details
                                            </flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-zinc-500 dark:text-zinc-400">
                                    No CPAR requests found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </flux:modal>
</div>