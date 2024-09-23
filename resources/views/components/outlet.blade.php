<div class="space-y-4 md:space-y-8">
    @isset($title)
        <div class="text-slate-700">
            <div class="flex justify-between items-center space-x-4">
                {{$prefix ?? ''}}
                <h1 class="font-bold text-xl">{{$title}}</h1>
                @isset($actions)
                    <div class="grow"></div>
                    <div class="space-x-2">
                        {{$actions}}
                    </div>
                @endisset
            </div>
        </div>
    @endif
    {{$slot}}
</div>
