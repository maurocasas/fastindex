<div wire:poll>
    <x-outlet>
        @if(filled($site))
            <x-slot:title>
                <a href="{{route('sites.show', $site)}}" class="link">{{$site->hostname}}</a> pages
            </x-slot:title>
        @else
            <x-slot:title>
                Pages
            </x-slot:title>
        @endif
        @if($pages->isEmpty())
            <div class="p-8 text-slate-600 rounded text-center space-y-4">
                <h2 class="text-xl font-medium">Nothing here yet</h2>
                <p>
                    You'll get a birdseye view of all the pages for your sites.
                </p>
            </div>
        @else
            <div class=" border bg-white rounded-lg shadow-sm border-slate-100">
                <x-pages-table :site="$site" :pages="$pages"/>
            </div>
            <div>
                {{$pages->links()}}
            </div>
        @endif
    </x-outlet>
</div>
