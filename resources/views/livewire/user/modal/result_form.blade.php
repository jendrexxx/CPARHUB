<div>
    <div>
        <flux:modal
            name="result-form"
            class="w-full max-w-7xl">
            <div class="mb-6 border-b border-zinc-200 pb-4 dark:border-zinc-700">
                <flux:heading size="lg">
                    Result Details
                </flux:heading>

                <flux:text class="mt-1 text-zinc-500">
                    Review the result details.
                </flux:text>
            </div>
            {{-- MAIN CONTENT --}}
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                <div>

                    {{-- Result No + Date --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                        <div>
                            <flux:label>Result No.</flux:label>

                            <flux:input
                                class="mt-2"
                                value="{{ $result_no ?? 'N/A' }}"
                                disabled />
                        </div>

                        <div>
                            <flux:label>Date Reported</flux:label>

                            <flux:input
                                class="mt-2"
                                value="{{ $date_reported ?? 'N/A' }}"
                                disabled />
                        </div>

                    </div>

                    {{-- Reported By + Department --}}
                    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">

                        <div>
                            <flux:label>Reported By</flux:label>

                            <flux:input
                                class="mt-2"
                                value="{{ $reported_by ?? 'N/A' }}"
                                disabled />
                        </div>

                        <div>
                            <flux:label>Patient Name</flux:label>

                            <flux:input
                                class="mt-2"
                                value="{{ $patient_name ?? 'N/A' }}"
                                disabled />
                        </div>

                    </div>

                    {{-- Patient + Physician --}}
                    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">

                        <div>
                            <flux:label>Attending Physician</flux:label>

                            <flux:input
                                class="mt-2"
                                value="{{ $attending_physician ?? 'N/A' }}"
                                disabled />
                        </div>

                        <div>
                            <flux:label>Actual Released Date</flux:label>

                            <flux:input
                                class="mt-2"
                                value="{{ $actual_released_date ?? 'N/A' }}"
                                disabled />
                        </div>

                    </div>

                    {{-- Released Date + Source --}}
                    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">

                        <div>
                            <flux:label>Source of Information</flux:label>

                            <flux:input
                                class="mt-2"
                                value="{{ $source_name ?? 'N/A' }}"
                                disabled />
                        </div>

                        <div>
                            <flux:label>Complainant Category</flux:label>

                            <flux:input
                                class="mt-2"
                                value="{{ $complain_name ?? 'N/A' }}"
                                disabled />
                        </div>

                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">

                        <div>
                            <flux:label>Complainant Name</flux:label>

                            <flux:input
                                class="mt-2"
                                value="{{ $complainant_name ?? 'N/A' }}"
                                disabled />
                        </div>

                        <div>
                            <flux:label>Priority</flux:label>

                            <flux:input
                                class="mt-2"
                                value="{{ $priority ?? 'N/A' }}"
                                disabled />
                        </div>

                    </div>

                    <div class="mt-4">
                        <flux:label>Test Procedure</flux:label>

                        <flux:textarea
                            class="mt-2"
                            rows="4"
                            disabled>{{ $test_procedure ?? 'N/A' }}</flux:textarea>
                    </div>

                    {{-- Concern Description --}}
                    <div class="mt-4">
                        <flux:label>Concern Description</flux:label>

                        <flux:textarea
                            class="mt-2"
                            rows="4"
                            disabled>{{ $concern_description ?? 'N/A' }}</flux:textarea>
                    </div>
                </div>
                <div>
                    {{-- Result Error Information --}}
                    <div class="mt-6">
                        {{-- Result Error / Concern --}}
                        <div class="mt-6">
                            <flux:label>
                                Result Error / Concern
                            </flux:label>
                            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-4">
                                {{-- Data and Information Errors --}}
                                <div class="rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">
                                    <div class="mb-4 border-b border-zinc-200 pb-2 dark:border-zinc-700">
                                        <flux:text class="font-medium">
                                            Data and Information Errors
                                        </flux:text>
                                    </div>
                                    <div class="space-y-3">
                                        @foreach ($data as $item)
                                        <label class="flex items-center gap-3 cursor-not-allowed">
                                            <input
                                                type="checkbox"
                                                value="{{ $item->id }}"
                                                wire:model="data_information"
                                                disabled
                                                class="h-4 w-4 rounded border-zinc-300
                                            text-primary-600
                                            disabled:cursor-not-allowed
                                            disabled:opacity-70
                                            dark:border-zinc-600">
                                            <span class="text-sm text-zinc-700 dark:text-zinc-300">
                                                {{ $item->data_name }}
                                            </span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Technical and Equipment Issues --}}
                                <div class="rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">
                                    <div class="mb-4 border-b border-zinc-200 pb-2 dark:border-zinc-700">
                                        <flux:text class="font-medium">
                                            Technical and Equipment Issues
                                        </flux:text>
                                    </div>
                                    <div class="space-y-3">
                                        @foreach ($technical as $item)
                                        <label class="flex items-center gap-3 cursor-not-allowed">
                                            <input
                                                type="checkbox"
                                                value="{{ $item->id }}"
                                                wire:model="technical_information"
                                                disabled
                                                class="h-4 w-4 rounded border-zinc-300
                                            text-primary-600
                                            disabled:cursor-not-allowed
                                            disabled:opacity-70
                                            dark:border-zinc-600">

                                            <span class="text-sm text-zinc-700 dark:text-zinc-300">
                                                {{ $item->technical_name }}
                                            </span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            {{-- Quality and Accuracy Issues --}}
                            <div class="rounded-lg border border-zinc-200 p-4 dark:border-zinc-700 mt-2">
                                <div class="mb-4 border-b border-zinc-200 pb-2 dark:border-zinc-700">
                                    <flux:text class="font-medium">
                                        Quality and Accuracy Issues
                                    </flux:text>
                                </div>
                                <div class="space-y-3">
                                    @foreach ($quality as $item)
                                    <label class="flex items-center gap-3 cursor-not-allowed">

                                        <input
                                            type="checkbox"
                                            value="{{ $item->id }}"
                                            wire:model="quality_information"
                                            disabled
                                            class="h-4 w-4 rounded border-zinc-300
                                    text-primary-600
                                    disabled:cursor-not-allowed
                                    disabled:opacity-70
                                    dark:border-zinc-600">
                                        <span class="text-sm text-zinc-700 dark:text-zinc-300">
                                            {{ $item->quality_name }}
                                        </span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- MODAL FOOTER --}}
            <div class="mt-8 flex items-center justify-end gap-3 border-t border-zinc-200 pt-4 dark:border-zinc-700">

                <flux:button
                    type="button"
                    variant="ghost"
                    x-on:click="$dispatch('modal-close', { name: 'result-form' })">
                    Close
                </flux:button>

                <flux:button
                    type="button"
                    variant="danger"
                    wire:click="confirmCancelResult({{ $assignment_id }})">
                    Cancel RESULT
                </flux:button>

            </div>
        </flux:modal>
    </div>
</div>