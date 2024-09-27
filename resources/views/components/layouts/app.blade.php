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
<div class="grid grid-cols-12 h-screen">
    <div class="col-span-2 p-8 flex flex-col space-y-16 h-screen">
        <x-application-logo class="text-lg"/>
        <div class="space-y-3">
            <a href="{{route('dashboard')}}" wire:navigate.hover
               class="nav-link {{request()->is('/') || request()->is('sites*') ? 'active' : ''}}">
                <x-phosphor.icons::regular.globe-simple class="size-4"/>
                <span>Sites</span>
            </a>
            <a href="{{route('pages')}}" wire:navigate.hover
               class="nav-link {{request()->is('*pages*') ? 'active' : ''}}">
                <x-phosphor.icons::regular.files class="size-4"/>
                <span>Pages</span>
            </a>
            @can('admin')
                <a href="{{route('service-accounts')}}"
                   class="nav-link {{request()->is('*service*') ? 'active' : ''}}" wire:navigate.hover>
                    <x-phosphor.icons::regular.robot class="size-4"/>
                    <span>Service accounts</span>
                </a>
                <a href="{{route('team')}}"
                   class="nav-link {{request()->is('*team*') ? 'active' : ''}}" wire:navigate.hover>
                    <x-phosphor.icons::regular.users class="size-4"/>
                    <span>Team</span>
                </a>
            @endcan
        </div>
        <div class="space-y-3">
            @foreach(Site::all() as $site)
                <a href="{{route('sites.show', $site)}}" wire:navigate.hover class="nav-link">
                    @if(filled($site->favicon))
                        <img src="{{$site->favicon}}" alt="{{$site->hostname}}" class="size-4 md:size-6"/>
                    @endif
                    <span>{{$site->hostname}}</span>
                </a>
            @endforeach
        </div>
        <div class="grow"></div>
        <div class="space-y-3">
            @can('admin')
                <a href="{{route('settings')}}" class="nav-link {{request()->is('*settings*') ? 'active' : ''}}"
                   wire:navigate.hover>
                    <x-phosphor.icons::regular.gear class="size-4"/>
                    <span>Settings</span>
                </a>
                <a href="https://github.com/maurocasas/fastindex/wiki" class="nav-link" target="_blank">
                    <x-phosphor.icons::regular.book-open class="size-4"/>
                    <span>Wiki</span>
                </a>
            @endcan
            <a href="{{route('account')}}" wire:navigate.hover
               class="nav-link {{request()->is('account*') ? 'active' : ''}}">
                <x-phosphor.icons::regular.user class="size-4"/>
                <span>Account</span>
            </a>
        </div>
    </div>
    <div class="col-span-10 flex flex-col h-screen overflow-y-scroll p-4 md:p-8 space-y-4 md:space-y-8">
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
