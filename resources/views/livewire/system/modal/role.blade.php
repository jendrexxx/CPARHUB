<div>
    <flux:modal name="role-modal" class="w-full max-w-lg">

        <form wire:key="user-form-{{ $role_id ?: 'new' }}" wire:submit="save">

            <flux:heading size="lg">
                {{ $role_id ? 'Edit Role' : 'Add Role' }}
            </flux:heading>

            <div class="mt-6">
                <flux:input
                    label="Role Name"
                    wire:model="name" />
            </div>

            <div class="mt-6">

                <flux:heading size="sm">
                    Permissions
                </flux:heading>

                <div class="grid grid-cols-2 gap-3 mt-3">
                    @foreach($permissions as $permission)
                    <label class="flex items-center gap-2">
                        <input
                            type="checkbox"
                            value="{{ $permission->name }}"
                            wire:model="selectedPermissions">
                        <span>{{ $permission->name }}</span>
                    </label>
                    @endforeach
                </div>


                <div class="flex justify-end gap-2 mt-6">

                    <flux:button
                        type="button"
                        variant="ghost"
                        x-on:click="$flux.modal('role-modal').close()">
                        Cancel
                    </flux:button>

                    <flux:button
                        type="submit"
                        variant="primary">
                        {{ $role_id ? 'Update' : 'Save' }}
                    </flux:button>

                </div>

        </form>

    </flux:modal>
</div>