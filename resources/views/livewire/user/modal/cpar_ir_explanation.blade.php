<flux:modal
    name="view-incident-report-request"
    class="w-[120%] max-w-[1100px] mt-6 top-0 z-50"
    wire:model="showViewIr">

    <div class="space-y-6">


        {{-- Header --}}
        <div class="flex items-start justify-between border-b pb-4">

            <div>
                <flux:heading size="lg">
                    Incident Report Request
                </flux:heading>

                <flux:text class="mt-1">
                    View the issued Incident Report request.
                </flux:text>
            </div>

        </div>


        {{-- IR Information --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">


            {{-- IR No --}}
            <flux:input
                label="IR No."
                wire:model="viewIrNo"
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
                label="Issued At"
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
                wire:click="submitIrResponse"
                wire:loading.attr="disabled"
                wire:target="submitIrResponse">


                <span
                    wire:loading.remove
                    wire:target="submitIrResponse">

                    Submit Incident Report

                </span>


                <span
                    wire:loading
                    wire:target="submitIrResponse">

                    Submitting...

                </span>


            </flux:button>


        </div>


    </div>


</flux:modal>