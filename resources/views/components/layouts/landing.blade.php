<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @isset($title)
        <title>{{ $title }}</title>
    @endisset
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @include('partials.fonts')
</head>
<body class="bg-white">
<div class="flex flex-col min-h-screen space-y-4">
    <nav class="p-4 md:py-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex flex-col justify-center md:flex-row md:justify-between">
                <x-application-logo class="text-lg md:text-2xl shrink-0"/>
                <div class="md:space-x-8 w-full flex justify-between md:justify-end items-center mt-2 md:mt-0">
                    <a href="#how-it-works" class="md:text-lg hover:underline">
                        How it works
                    </a>
                    <a href="#pricing" class="md:text-lg hover:underline">
                        Pricing
                    </a>
                    <a href="#faq" class="md:text-lg hover:underline">
                        FAQ
                    </a>
                    @guest
                        <a href="{{route('google-auth')}}"
                           class="btn btn-sm md:btn-base md:text-lg btn-primary text-white">
                            <x-phosphor.icons::bold.google-logo class="size-4"/>
                            Login <span class="hidden md:inline">with Google</span>
                        </a>
                    @else
                        <a href="{{route('dashboard')}}"
                           class="btn btn-sm md:btn-base md:text-lg btn-primary text-white">
                            Dashboard
                            <x-phosphor.icons::bold.arrow-right class="size-4"/>
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>
    <main class="px-4 grow">
        <div class="max-w-4xl mx-auto">
            {{$slot}}
        </div>
    </main>
    <footer class="p-4">
        <div class="max-w-4xl mx-auto flex items-center justify-between border-t text-black/60 border-dotted pt-4">
            <div>
                &copy; FastIndex
            </div>
            <div class="grow"></div>
            <div class="space-x-2 flex items-center">
                <a href="/terms" class="btn btn-ghost btn-sm">Terms</a>
                <a href="/privacy" class="btn btn-ghost btn-sm">Privacy</a>
                <a href="https://twitter.com/maurohouseless" target="_blank" class="btn btn-ghost btn-sm">
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
