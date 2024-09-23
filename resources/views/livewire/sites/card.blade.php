<a class="border hover:border-slate-200 transition-all group border-slate-100 rounded-lg shadow-sm space-y-8 p-4 md:p-8 text-sm"
   href="{{route('sites.show', $site)}}" wire:navigate.hover>
    <div class="space-y-4">
        <div class="flex items-center space-x-2 ">
            @if(filled($site->favicon))
                <div class="size-6 md:size-8 overflow-hidden rounded">
                    <img src="{{$site->favicon}}" alt="{{$site->hostname}}" class="size-6 md:size-8"/>
                </div>
            @endif
            <div class="text-sm font-medium">
                {{$site->hostname}}
            </div>
        </div>

        <div>
            @if($site->auto_index)
                <span
                    class="text-xs text-emerald-500 font-medium bg-emerald-50 border border-emerald-100 py-1 px-3 rounded-lg md:rounded-xl">
                Auto-indexing: on
            </span>
            @else
                <span
                    class="text-xs text-amber-500 font-medium bg-amber-50 border border-amber-100 py-1 px-3 rounded-lg md:rounded-xl">
                Auto-indexing: off
            </span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-3 gap-2 text-sm">
        <div class="flex flex-col items-center p-2 bg-emerald-50 border-emerald-100 border text-emerald-800 rounded-lg">
            <span class="font-medium">
                Indexed
            </span>
            <span>
                {{$this->indexed}}
            </span>
        </div>
        <div class="flex flex-col items-center p-2 bg-slate-50 border-slate-100 border text-slate-800 rounded-lg">
            <span class="font-medium">
                Pending
            </span>
            <span>
                {{$this->pending}}
            </span>
        </div>
        <div class="flex flex-col items-center p-2 bg-red-50 border-red-100 border text-red-800 rounded-lg">
            <span class="font-medium">
                Errors
            </span>
            <span>
                {{$this->errors}}
            </span>
        </div>
    </div>

    @if($this->service_accounts() > 1)
        <div>
            <x-status-badge status="error">
                You're at risk, more than 1 service account linked.
            </x-status-badge>
        </div>
    @endif

    @if($this->service_accounts() < 1)
        <div>
            <x-status-badge status="error">
                <x-phosphor.icons::bold.exclamation-mark class="size-4"/>
                <span>No service accounts linked, site not being indexed.</span>
            </x-status-badge>
        </div>
    @endif

    <div class="flex items-center justify-between">
        <span class="text-sm text-slate-400">Updated {{$site->updated_at->diffForHumans()}}</span>
        <span class="btn btn-outline group-hover:ring-slate-200">
            <span>Manage</span>
            <x-phosphor.icons::bold.arrow-right class="size-4"/>
        </span>
    </div>
</a>
