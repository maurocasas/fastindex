<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'FastIndex' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @include('partials.fonts')
</head>
<body class="bg-white">
<div class="flex flex-col min-h-screen">
    <main class="grow">
        {{$slot}}
    </main>
    <footer class="p-4 opacity-60">
        <div class="container mx-auto flex items-center justify-between">
            <div class="text-sm">
                Powered by FastIndex
            </div>
            <div class="grow"></div>
            <div class="space-x-4 flex items-center">
                <a href="mailto:contact@indexcoach.so" class="text-sm">Support</a>
                <a href="https://twitter.com/maurohouseless" target="_blank" class="text-sm">
                    <x-phosphor.icons::bold.twitter-logo class="size-6"/>
                </a>
            </div>
        </div>
    </footer>
</div>
<x-toaster-hub/>
@livewireScripts
@livewireScriptConfig
@stack('modals')
</body>
</html>
