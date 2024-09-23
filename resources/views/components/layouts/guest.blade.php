@php use App\Models\Site; @endphp
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ filled($title) ? "{$title} - FastIndex" : 'FastIndex' }}</title>
    @persist('assets')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endpersist
    @include('partials.fonts')
</head>
<body class="bg-white">
<div class="flex items-center justify-center flex-col w-full h-screen">
    <div class="space-y-8">
        <x-application-logo class="text-lg"/>
        <div class="grow ">
            {{$slot}}
        </div>
        <div class="flex items-center space-x-4 text-sm text-slate-400">
            <span>Powered by FastIndex</span>
            <a href="https://github.com/maurocasas/fastindex" target="_blank" class="hover:text-slate-800">
                <x-phosphor.icons::regular.github-logo class="size-5"/>
            </a>
            <a href="https://twitter.com/maurohouseless" target="_blank" class="hover:text-slate-800">
                <x-phosphor.icons::regular.x-logo class="size-5"/>
            </a>
        </div>
    </div>
</div>
<x-toaster-hub/>
@stack('modals')
</body>
</html>
