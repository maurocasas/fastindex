<x-outlet>
    <x-slot:title>
        Account
        <x-slot:actions>
            <livewire:auth.logout/>
        </x-slot:actions>
    </x-slot:title>
    <form wire:submit="updateProfile" class="max-w-md space-y-4">
        <h3 class="font-medium">Update profile</h3>
        <div class="space-y-2 flex flex-col">
            <label for="name" class="label">Name</label>
            <input id="name" name="name" type="text" class="input"
                   required wire:model="name"/>
            @error('name')
            <div class="validation-error">{{ $message }}</div>
            @enderror
        </div>
        <div class="space-y-2 flex flex-col">
            <label for="email" class="label">Email</label>
            <input id="email" name="email" type="text" class="input"
                   required wire:model="email"/>
            @error('email')
            <div class="validation-error">{{ $message }}</div>
            @enderror
        </div>
        <button class="btn btn-primary" wire:loading.attr="disabled" wire:target="updateProfile">
            <span>Save</span>
            <x-phosphor.icons::bold.circle-notch class="size-4 animate-spin" wire:loading.inline-block wire:target="updateProfile" />
        </button>
    </form>
    <form wire:submit="updatePassword" class="max-w-md space-y-4">
        <h3 class="font-medium">Update password</h3>
        <div class="space-y-2 flex flex-col">
            <label for="current_password" class="label">Current password</label>
            <input id="current_password" name="current_password" type="password" class="input"
                   required wire:model="current_password"/>
            @error('current_password')
            <div class="validation-error">{{ $message }}</div>
            @enderror
        </div>
        <div class="space-y-2 flex flex-col">
            <label for="new_password" class="label">New password</label>
            <input id="new_password" name="new_password" type="password" class="input"
                   required wire:model="new_password"/>
            @error('new_password')
            <div class="validation-error">{{ $message }}</div>
            @enderror
        </div>
        <div class="space-y-2 flex flex-col">
            <label for="new_password_confirmation" class="label">Repeat new password</label>
            <input id="new_password_confirmation" name="new_password_confirmation" type="password" class="input"
                   required wire:model="new_password_confirmation"/>
            @error('new_password_confirmation')
            <div class="validation-error">{{ $message }}</div>
            @enderror
        </div>
        <button class="btn btn-primary" wire:loading.attr="disabled" wire:target="updatePassword">
            <span>Save</span>
            <x-phosphor.icons::bold.circle-notch class="size-4 animate-spin" wire:loading.inline-block wire:target="updatePassword"/>
        </button>
    </form>
</x-outlet>
