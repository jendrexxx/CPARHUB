<div>
    <flux:modal name="user-permission" class="w-full max-w-2xl">

        <flux:heading size="lg">
            User Permissions
        </flux:heading>

        <flux:text class="mt-1 text-gray-600">
            Assign roles and permissions.
        </flux:text>

        <div class="mt-6">

            <flux:select
                label="Role"
                wire:model="role">
                <option value="">-- Select Role --</option>
                @foreach($roles as $role)
                <option value="{{ $role->name }}">
                    {{ $role->name }}
                </option>
                @endforeach
            </flux:select>

        </div>
        <div class="grid grid-cols-2 gap-3 mt-3">

            @foreach($permission as $item)

            <label class="flex items-center gap-2">

                <input
                    type="checkbox"
                    value="{{ $item->name }}"
                    wire:model="selectedPermissions">
                <span>
                    {{ $item->name }}
                </span>


            </label>

            @endforeach

        </div>

        <div class="flex justify-end gap-2 mt-6">

            <flux:button
                variant="ghost"
                x-on:click="$flux.modal('user-permission').close()">

                Close

            </flux:button>

            <flux:button
                variant="primary"
                wire:click="savePermission">

                Save

            </flux:button>

        </div>

    </flux:modal>
</div>