<flux:modal
    name="view-notice-to-explain"
    class="w-[120%] max-w-[1100px] mt-6 top-0 z-50"
    wire:model="showViewNte">

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-start justify-between border-b pb-4">

            <div>
                <flux:heading size="lg">
                    Notice to Explain
                </flux:heading>

                <flux:text class="mt-1">
                    View the issued Notice to Explain.
                </flux:text>
            </div>

            {{-- Status --}}
            <div>

                @if($viewStatus === 'SENT')

                <flux:badge color="blue">
                    SENT
                </flux:badge>

                @elseif($viewStatus === 'SUBMITTED')

                <flux:badge color="yellow">
                    SUBMITTED
                </flux:badge>

                @elseif($viewStatus === 'UNDER REVIEW')

                <flux:badge color="orange">
                    UNDER REVIEW
                </flux:badge>

                @elseif($viewStatus === 'RESOLVED')

                <flux:badge color="green">
                    RESOLVED
                </flux:badge>

                @elseif($viewStatus === 'CLOSED')

                <flux:badge color="green">
                    CLOSED
                </flux:badge>

                @else

                <flux:badge>
                    {{ $viewStatus ?: 'DRAFT' }}
                </flux:badge>

                @endif

            </div>

        </div>


        {{-- NTE Information --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            {{-- NTE No --}}
            <flux:input
                label="NTE No."
                wire:model="viewNteNo"
                readonly />

            {{-- CPAR No --}}
            <flux:input
                label="CPAR No."
                wire:model="viewCparNo"
                readonly />

            {{-- Department --}}
            <flux:input
                label="Department"
                wire:model="viewDepartment"
                readonly />

        </div>


        {{-- Employee --}}
        <div>

            <flux:input
                label="Employee"
                wire:model="viewEmployeeName"
                readonly />

        </div>


        {{-- Dates --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <flux:input
                label="Issued Date"
                wire:model="viewIssuedAt"
                readonly />

            <flux:input
                label="Due Date"
                wire:model="viewDueDate"
                readonly />
        </div>

        {{-- Footer --}}
        <div class="flex justify-end gap-2 pt-4 border-t">
            <flux:button
                variant="primary"
                icon="paper-airplane"
                wire:click="submitNteResponse"
                wire:loading.attr="disabled"
                wire:target="submitNteResponse">
                <span
                    wire:loading.remove
                    wire:target="submitNteResponse">
                    Submit Response
                </span>
                <span
                    wire:loading
                    wire:target="submitNteResponse">
                    Submitting...
                </span>
            </flux:button>
        </div>

    </div>

</flux:modal>