<flux:modal name="RESULTModal" class="w-[120%] max-w-[1500px] mt-6 top-0 z-50">

    <div class="space-y-6">

        <div>
            <flux:heading size="lg">
                Result Request
            </flux:heading>

            <flux:text class="mt-1">
                Below is the list of your filed Result Concern requests.
            </flux:text>
        </div>

        <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700 text-center">
            <div class="w-full overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
                <table class="w-full text-sm text-center">
                    <thead class="bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-center">Result No.</th>
                            <th class="px-4 py-3 text-center">Patient Name</th>
                            <th class="px-4 py-3 text-center">Source Name</th>
                            <th class="px-4 py-3 text-center">Complainant</th>
                            <th class="px-4 py-3 text-center">Date Reported</th>
                            <th class="px-4 py-3 text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                        @forelse($resultRequests as $request)

                        <tr>

                            <td class="px-4 py-3 font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $request->result_no }}
                            </td>

                            <td class="px-4 py-3 font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $request->patient_name }}
                            </td>

                            <td class="px-4 py-3 font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $request->source_name }}
                            </td>

                            <td class="px-4 py-3 font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $request->complain_name }}
                            </td>

                            <td class="px-4 py-3 font-medium text-zinc-900 dark:text-zinc-100">
                                {{ \Carbon\Carbon::parse($request->date_reported)->format('M d, Y') }}
                            </td>

                            <td class="px-4 py-3 text-center">
                                <flux:button
                                    type="button"
                                    wire:click="viewResult"
                                    size="sm"
                                    variant="ghost"
                                    icon="eye">
                                    View
                                </flux:button>
                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-zinc-500">
                                No Result Concern requests found.
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>
        </div>

    </div>
    <div>
        <table>
            <tbody>
                <tr>
                    <td class="px-4 py-3 text-center">
                        <flux:button
                            type="button"
                            wire:click="viewResult"
                            size="sm"
                            variant="ghost"
                            icon="eye">
                            View
                        </flux:button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</flux:modal>