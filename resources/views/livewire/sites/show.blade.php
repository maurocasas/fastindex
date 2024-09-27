<div x-on:close-modal="document.getElementById('new_sitemap').close();" wire:poll>
    <x-outlet>
        <x-slot:title>
            {{$site->hostname}}
            @if(filled($site->favicon))
                <x-slot:prefix>
                    <div class="size-6 md:size-8 overflow-hidden rounded">
                        <img src="{{$site->favicon}}" alt="{{$site->hostname}}" class="size-6 md:size-8"/>
                    </div>
                </x-slot:prefix>
            @endif
        </x-slot:title>

        <x-slot:actions>
            @if($this->service_accounts > 0)
                <button wire:click="toggleAutoindex" class="btn btn-outline" wire:loading.attr="disabled"
                        wire:target="toggleAutoindex">
                    <span>{{$site->auto_index ? 'Disable' : 'Enable'}} auto-indexing</span>
                    <x-phosphor.icons::bold.magnifying-glass class="size-4" wire:loading.remove
                                                             wire:target="toggleAutoindex"/>
                    <x-phosphor.icons::bold.circle-notch class="size-4 animate-spin" wire:loading.inline-block
                                                         wire:target="toggleAutoindex"/>
                </button>
            @endif
            <button wire:click="getSitemaps" class="btn" wire:loading.attr="disabled" wire:target="getSitemaps"
                    @if($site->refreshing_sitemaps) disabled @endif>
                <span>Sync sitemaps with Search Console</span>
                @if($site->refreshing_sitemaps)
                    <x-phosphor.icons::bold.arrows-clockwise class="size-4 animate-spin"/>
                @else
                    <x-phosphor.icons::bold.arrows-clockwise class="size-4" wire:loading.class="animate-spin"
                                                             wire:target="getSitemaps"/>
                @endif
            </button>
        </x-slot:actions>

        @persist('modal')
        <dialog id="new_sitemap" class="p-8 space-y-8 rounded-lg md:rounded-xl w-full max-w-md" wire:ignore.self>
            <div class="flex items-center justify-between">
                <h3 class="font-medium text-lg">New sitemap</h3>
                <button type="button" x-on:click="document.getElementById('new_sitemap').close();">
                    <x-phosphor.icons::bold.x class="size-4"/>
                </button>
            </div>
            <form wire:submit.prevent="createSitemap" class="space-y-8">
                <div class="space-y-2 flex flex-col max-w-sm">
                    <label for="name" class="label">
                        Sitemap URL
                    </label>
                    <input type="text" id="sitemap_url" wire:model="sitemap_url" required class="input"/>
                    @error('sitemap_url')
                    <div class="validation-error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn" wire:loading.attr="disabled" wire:target="createSitemap">
                    <span>Submit</span>
                    <x-phosphor.icons::bold.circle-notch class="size-4 animate-spin" wire:loading.inline-block
                                                         wire:target="createSitemap"/>
                </button>
            </form>
        </dialog>
        @endpersist

        @if(!$this->service_accounts > 1)
            <x-alert type="error">
                <div class="flex w-full items-center justify-between">
                    <div>
                        You have {{$this->service_accounts}} service accounts linked to this site, you're at risk of
                        being banned by Google.
                    </div>
                    <a href="/help" class="inline-flex items-center space-x-1 font-medium text-red-500">
                        <span>Read more</span>
                        <x-phosphor.icons::bold.arrow-right class="size-4"/>
                    </a>
                </div>
            </x-alert>
        @endif

        @if($this->service_accounts === 0)
            <x-alert type="error">
                <div class="flex w-full items-center justify-between">
                    <div>
                        No service accounts linked to this site. <b>Pages are not being indexed.</b>
                    </div>
                </div>
            </x-alert>
        @elseif(!$site->auto_index)
            <x-alert type="warning">
                Site is not being auto-indexed.
            </x-alert>
        @endif

        <div class="grid grid-cols-3 gap-2 text-sm md:text-base ">
            <div
                class="flex flex-col items-center p-2 bg-emerald-50 text-emerald-800 border border-emerald-100 rounded-lg ">
            <span class="font-medium">
                Indexed
            </span>
                <span>
                {{$this->indexed}}
            </span>
            </div>
            <div class="flex flex-col items-center p-2 bg-slate-50 text-slate-800 rounded-lg border border-slate-100">
            <span class="font-medium">
                Pending
            </span>
                <span>
                {{$this->pending}}
            </span>
            </div>
            <div class="flex flex-col items-center p-2 bg-red-50 text-red-800 rounded-lg border border-red-100">
            <span class="font-medium">
                Errors
            </span>
                <span>
                {{$this->errors}}
            </span>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <h2 class="font-medium">Sitemaps</h2>
            <button type="button" class="btn btn-outline"
                    x-on:click="document.getElementById('new_sitemap').showModal();">
                <span>New sitemap</span>
                <x-phosphor.icons::bold.plus class="size-4"/>
            </button>
        </div>
        <div class=" border bg-white rounded-lg shadow-sm border-slate-100">
            <table class="w-full text-sm">
                <thead class="text-left border-b border-slate-100">
                <tr class="*:py-3">
                    <th class="pl-3">Path</th>
                    <th class="w-[15%]">Last crawl</th>
                    <th class="w-[10%]">&nbsp;</th>
                </tr>
                </thead>
                <tbody>
                @forelse($this->sitemaps as $sitemap)
                    <tr class="odd:bg-slate-100/20 *:py-2">
                        <td class="pl-3">
                            <a href="{{$sitemap->url}}" target="_blank"
                               class="inline-flex space-x-1 link items-center">
                                <span>{{$sitemap->url}}</span>
                                <x-phosphor.icons::bold.arrow-square-out class="size-3"/>
                            </a>
                        </td>
                        <td>
                            @if(filled($sitemap->downloaded_at))
                                <span class="underline decoration-dotted"
                                      title="{{$sitemap->downloaded_at->toDateTimeString()}}">
                                    {{$sitemap->downloaded_at->diffForHumans()}}
                                </span>
                            @else
                                <x-status-badge>
                                    Not crawled yet.
                                </x-status-badge>
                            @endif
                        </td>
                        <td class="pr-3 text-right">
                            <button class="btn btn-outline !text-red-500" wire:click="deleteSitemap({{$sitemap->id}})"
                                    wire:confirm="Are you sure you want to delete this sitemap?">
                                <span>Delete</span>
                                <x-phosphor.icons::bold.trash class="size-4"/>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="p-3">
                            No sitemaps available.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between">
            <h2 class="font-medium">Pages</h2>
            <a href="{{route('pages', $site)}}" class="btn btn-outline" wire:navigate.hover>
                <span>View all {{$this->totalPages}} pages</span>
                <x-phosphor.icons::bold.files class="size-4"/>
            </a>
        </div>
        <div class=" border bg-white rounded-lg shadow-sm border-slate-100">
            <x-pages-table :site="$site" :pages="$this->pages"/>
        </div>
    </x-outlet>
</div>
