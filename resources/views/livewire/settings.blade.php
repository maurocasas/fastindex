<x-outlet>
    <x-slot:title>Settings</x-slot:title>
    <form wire:submit="store" class="max-w-md space-y-4">
        <div class="space-y-2 flex flex-col">
            <label for="daily_quota" class="label">Daily quota</label>
            <input id="daily_quota" name="daily_quota" type="text" class="input"
                   required wire:model="daily_quota"/>
            @error('daily_quota')
            <div class="validation-error">{{ $message }}</div>
            @enderror
            <div class="help-text">
                Although the maximum is 2000 daily writes/reads (combined) it's suggested to keep it under 1500.
            </div>
        </div>
        <button class="btn btn-primary" wire:loading.attr="disabled">
            <span>Save</span>
            <x-phosphor.icons::bold.circle-notch class="size-4 animate-spin" wire:loading.inline-block/>
        </button>
    </form>
</x-outlet>
