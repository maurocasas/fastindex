@php use App\AlertType; @endphp
<div @class([
        'border p-4 rounded-lg md:rounded-xl text-sm flex items-center space-x-2',
        'bg-red-50 border-red-100 text-red-500' => $type === AlertType::ERROR->value,
        'bg-amber-50 border-amber-100 text-amber-500' => $type === AlertType::WARNING->value,
        'bg-emerald-50 border-emerald-100 text-emerald-500' => $type === AlertType::SUCCESS->value,
        'bg-sky-50 border-sky-100 text-sky-500' => $type === AlertType::INFO->value,
])>
    {{$slot}}
</div>
