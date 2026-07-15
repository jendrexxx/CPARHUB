<div>

    <flux:modal name="user-create" class="w-full max-w-4xl">

        <form wire:submit="save">

            {{-- Header --}}
            <flux:heading size="lg">
                {{ $user_id ? 'Edit User' : 'Add New User' }}
            </flux:heading>

            <flux:text class="mt-1 text-gray-600">
                {{ $user_id ? 'Update user information.' : 'Enter the user details below.' }}
            </flux:text>

            {{-- Form --}}
            <div class="grid grid-cols-2 gap-4 mt-6">

                <flux:input
                    label="Employee No"
                    wire:model="employee_no" />

                <flux:input
                    label="Full Name"
                    wire:model="name" />

                <flux:input
                    label="Email"
                    type="email"
                    wire:model="email" />

                <flux:input
                    label="Username"
                    wire:model="username" />

                @if(!$user_id)

                    <flux:input
                        label="Password"
                        type="password"
                        wire:model="password" />

                    <flux:input
                        label="Confirm Password"
                        type="password"
                        wire:model="confirm_password" />

                @else

                    <flux:input
                        label="New Password"
                        type="password"
                        wire:model="password"
                        class="col-span-2" />

                @endif

                <flux:select
                    label="Department"
                    wire:model="department_name">

                    <option value="">-- Select Department --</option>

                    @foreach($department_list as $department)

                        <option value="{{ $department }}">
                            {{ $department }}
                        </option>

                    @endforeach

                </flux:select>

                <flux:select
                    label="Branch"
                    wire:model="branch_name">

                    <option value="">-- Select Branch --</option>

                    @foreach($branch_list as $branch)

                        <option value="{{ $branch }}">
                            {{ $branch }}
                        </option>

                    @endforeach

                </flux:select>

                <flux:select
                    label="Role"
                    wire:model="role">

                    <option value="">-- Select Role --</option>

                    @foreach($roles as $role)

                        <option value="{{ $role }}">
                            {{ $role }}
                        </option>

                    @endforeach

                </flux:select>

                <flux:select
                    label="Status"
                    wire:model="status">

                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>

                </flux:select>

            </div>

            {{-- Footer --}}
            <div class="flex justify-end gap-2 mt-6">

                <flux:button
                    type="button"
                    variant="ghost"
                    x-on:click="$flux.modal('user-create').close()">

                    Cancel

                </flux:button>

                <flux:button
                    type="submit"
                    variant="primary">

                    {{ $user_id ? 'Update User' : 'Save User' }}

                </flux:button>

            </div>

        </form>

    </flux:modal>

</div>