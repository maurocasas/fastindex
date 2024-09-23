<x-outlet wire:poll x-on:close-modal="document.getElementById('sync_sites').close();">
    <x-slot:title>Sites</x-slot:title>

    <x-slot:actions>
        <button class="btn btn-outline" type="button"
                x-on:click="document.getElementById('sync_sites').showModal();">
            <x-phosphor.icons::bold.arrows-clockwise class="size-4"/>
            <span>Sync sites</span>
        </button>
    </x-slot:actions>
    @if($sites->isEmpty())
        <div class="p-8 text-slate-600 rounded text-center space-y-4">
            <h2 class="text-xl font-medium">Let's get ready to rumble 🥁</h2>
            <p>
                Sites are automatically fetched from Google Search Console, <br> if you're not seeing yours
                please review your Google account.
            </p>
            <a class="btn" target="_blank"
               href="https://search.google.com/search-console">
                <span>Add your site on Google Search Console</span>
                <x-phosphor.icons::bold.arrow-square-out class="size-5"/>
            </a>
        </div>
    @endif
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($sites as $site)
            <livewire:sites.card :site="$site" wire:key="site-{{$site->id}}"/>
        @endforeach
    </div>
</x-outlet>

@push('modals')
    @persist('modal')
    <dialog id="sync_sites" class="p-8 space-y-8 rounded-lg md:rounded-xl w-full max-w-md" wire:ignore.self>
        <div class="flex items-center justify-between">
            <h3 class="font-medium text-lg">Sync sites with Google Search Console</h3>
            <button type="button" x-on:click="document.getElementById('sync_sites').close();">
                <x-phosphor.icons::bold.x class="size-4"/>
            </button>
        </div>
        <form wire:submit.prevent="syncSites" class="space-y-8">
            <p>
                FastIndex will poll each service account linked to find any missing sites.
            </p>
            <p>
                If you're not seeing your site listed make sure you have it under your GSC account.
            </p>
            <p>
                <a href="/help" class="link">Watch a video on how to do this.</a>
            </p>

            <button type="submit" class="btn" wire:loading.attr="disabled" wire:target="refresh">
                <span>Sync</span>
                <x-phosphor.icons::bold.circle-notch class="size-4 animate-spin" wire:loading.inline-block
                                                     wire:target="refresh"/>
            </button>
        </form>
    </dialog>
    @endpersist
@endpush
