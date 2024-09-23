<form wire:submit="store" class="space-y-4">
    <h3 class="font-semibold">Register sitemap</h3>
    <x-alert title="Sitemap will be registered automatically to your Google Search Console." info/>
    <x-input label="Sitemap URL" required type="url" id="path" name="path" wire:model="path" />
    <x-button primary type="submit" >
        <x-phosphor.icons::bold.circle-notch class="size-4 animate-spin" wire:loading/>
        <x-phosphor.icons::bold.check class="size-4" wire:loading.remove/>
        Submit
    </x-button>
</form>
