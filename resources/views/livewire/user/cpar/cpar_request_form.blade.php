<div>
    <flux class="max-w-5xl mx-auto">

        <div class="mb-6">
            <flux:heading size="xl" class="text-red-600">
                CPAR Request Form
            </flux:heading>

            <flux:text class="mt-1">
                Corrective and Preventive Action Request
            </flux:text>
        </div>

        <form class="space-y-6">

            {{-- CPAR No & Date --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <flux:input
                    label="CPAR No."
                    value="CPAR-2025-001"
                    readonly />

                <flux:input
                    label="Date Opened"
                    type="date" />

            </div>

            {{-- Source Origin --}}
            <flux:select label="Source Origin">

                <option value="">Select Source</option>
                <option>Internal Audit</option>
                <option>Customer Complaint</option>
                <option>Management Review</option>
                <option>Employee Suggestion</option>

            </flux:select>

            {{-- Reported By --}}
            <flux:input
                label="Reported By"
                placeholder="Juan Dela Cruz" />

            {{-- Complainant --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <flux:select
                    id="category"
                    label="Complainant Category">

                    <option value="">Select</option>
                    <option>Employee</option>
                    <option>Customer</option>
                    <option>Supplier</option>
                    <option>Others</option>

                </flux:select>

                <flux:input
                    id="complainant"
                    label="Complainant Name" />

            </div>

            {{-- Concern Description --}}
            <flux:textarea
                label="Concern Description"
                rows="5" />

            {{-- Attachment --}}
            <div>

                <flux:label>Attachment</flux:label>

                <input
                    type="file"
                    class="mt-2 block w-full rounded-lg border border-zinc-300 dark:border-zinc-700">

                <flux:text size="sm" class="mt-2">
                    Allowed files: JPG, PNG, PDF, DOC, DOCX
                </flux:text>

            </div>

            {{-- Concern Category --}}
            <flux:select label="Concern Category">

                <option value="">Select</option>
                <option>Process</option>
                <option>Quality</option>
                <option>Compliance</option>
                <option>Safety</option>

            </flux:select>

            {{-- Assigned To --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <flux:select label="Assigned To">

                    <option value="">Select Employee</option>
                    <option>John Doe</option>
                    <option>Jane Smith</option>

                </flux:select>

                <flux:input
                    label="Department"
                    value="Quality Assurance"
                    readonly />

            </div>

            <div class="flex justify-end">

                <flux:button
                    type="submit"
                    variant="primary">

                    Submit Request

                </flux:button>

            </div>

        </form>

    </flux>
</div>