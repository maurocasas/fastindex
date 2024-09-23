<div>
<a {{$attributes}} class="inline-flex items-center space-x-4 p-2 rounded
@if($active) bg-white text-orange-600 @else text-slate-500 hover:text-slate-800 hover:bg-slate-100  @endif
">
    @if(filled($icon))
        <x-dynamic-component :component="'phosphor.icons::'.$icon" class="size-6"/>
    @endif
    <span>{{$slot}}</span>
</a>
</div>
