<table class="w-full text-sm">
    <thead class="text-left border-b border-slate-100">
    <tr class="*:py-3">
        @if(blank($site))
            <th class="pl-3 w-[15%]">Site</th>
        @endif
        <th @class(['pl-3' => filled($site)])>Path</th>
        <th class="w-[15%]">Status</th>
        <th class="w-[10%]">Crawled at</th>
    </tr>
    </thead>
    <tbody>
    @foreach($pages as $page)
        <tr class="odd:bg-slate-100/20 *:py-2" wire:key="page_{{$page->id}}">
            @if(blank($site))
                <td class="pl-3">
                    <a href="{{route('sites.show', $page->site)}}"
                       class="link inline-flex space-x-1.5 items-center">
                        @if(filled($page->site->favicon))
                            <div class="size-4 overflow-hidden rounded">
                                <img src="{{$page->site->favicon}}" alt="{{$page->site->hostname}}"
                                     class="size-4"/>
                            </div>
                        @endif
                        <span>{{$page->site->hostname}}</span>
                    </a>
                </td>
            @endif
            <td @class(['flex items-center justify-between', 'pl-3' => filled($site)])>
                <a href="{{$page->url}}" target="_blank"
                   class="inline-flex items-center space-x-1 link">
                    <span>{{$page->path}}</span>
                    <x-phosphor.icons::bold.arrow-square-out class="size-3"/>
                </a>
            </td>
            <td>
                @if($page->not_found)
                    <x-status-badge status="error">
                        404 - Not found
                    </x-status-badge>
                @else
                    <x-status-badge :status="$page->status">
                        {{$page->coverage_state ?? 'Pending'}}
                    </x-status-badge>
                @endif
            </td>
            <td>
                @if(filled($page->crawled_at))
                    <span title="{{$page->crawled_at->toDateTimeString()}}"
                          class="underline decoration-dotted">
                                {{$page->crawled_at->diffForHumans()}}
                            </span>
                @else
                    &nbsp;
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
