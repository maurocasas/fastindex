@php use App\AlertType; @endphp
<span @class([
    'px-3 py-1.5 rounded-xl text-xs inline-flex items-center space-x-2 capitalize',
    'bg-slate-50 border border-slate-100 text-slate-400' => blank($status),
    'bg-sky-50 border border-sky-100 text-sky-400' => filled($status) && $status->value === AlertType::INFO->value,
    'bg-emerald-50 border border-emerald-100 text-emerald-500' => filled($status) && $status->value === AlertType::SUCCESS->value,
    'bg-amber-100 border border-amber-200 text-amber-600' => filled($status) && $status->value === AlertType::WARNING->value,
    'bg-red-50 border border-red-100 text-red-500' => filled($status) && $status->value === AlertType::ERROR->value,
])>
    {{$slot}}
</span>
