@php use App\UserRole; @endphp
<div x-on:close-modal="document.getElementById('new_member').close();">
    <x-outlet>
        <x-slot:title>Team</x-slot:title>
        <x-slot:actions>
            <button type="button" class="btn"
                    x-on:click="document.getElementById('new_member').showModal();">
                <x-phosphor.icons::bold.asterisk class="size-4"/>
                <span>New team member</span>
            </button>
        </x-slot:actions>
        <div class=" border bg-white rounded-lg shadow-sm border-slate-100">
            <table class="w-full text-sm">
                <thead class="text-left border-b border-slate-100">
                <tr class="*:py-3">
                    <th class="pl-3 w-[15%]">Email</th>
                    <th class="w-[15%]">Name</th>
                    <th class="w-[15%]">Role</th>
                    <th class="w-[10%]">Last login</th>
                    <th>&nbsp;</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr class="odd:bg-slate-100/20 *:py-2" wire:key="user_{{$user->id}}">
                        <td class="pl-3">{{$user->email}}</td>
                        <td>{{$user->name}}</td>
                        <td class="capitalize">{{$user->role->value}}</td>
                        <td>
                            {{$user->last_login_at?->diffForHumans() ?? 'No log-in yet.'}}
                        </td>
                        <td class="text-right pr-3">
                            @if($user->id !== auth()->id())
                                @if($user->role->value === UserRole::ADMIN->value)
                                    <button class="btn btn-outline" wire:click="toggleRole({{$user->id}})">
                                        Make member
                                    </button>
                                @else
                                    <button class="btn btn-outline" wire:click="toggleRole({{$user->id}})">
                                        Make admin
                                    </button>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @persist('modal')
        <dialog id="new_member" class="p-8 space-y-8 rounded-lg md:rounded-xl w-full max-w-md"
                wire:ignore.self>
            <div class="flex items-center justify-between">
                <h3 class="font-medium text-lg">New team member</h3>
                <button type="button" x-on:click="document.getElementById('new_service_account').close();">
                    <x-phosphor.icons::bold.x class="size-4"/>
                </button>
            </div>
            <form wire:submit.prevent="create" class="space-y-8">
                <div class="space-y-2 flex flex-col">
                    <label class="label" for="name">Name</label>
                    <input id="name" name="name" type="text" class="input" placeholder="Name" autofocus
                           required wire:model="name"/>
                    @error('name')
                    <div class="validation-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="space-y-2 flex flex-col">
                    <label class="label" for="email">Email</label>
                    <input id="email" name="email" type="email" class="input" placeholder="Email"
                           required wire:model="email"/>
                    @error('email')
                    <div class="validation-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="space-y-2 flex flex-col">
                    <label class="label" for="password">Password</label>
                    <input id="password" name="password" type="password" class="input" placeholder="Password"
                           required wire:model="password"/>
                    @error('password')
                    <div class="validation-error">{{ $message }}</div>
                    @enderror
                </div>
                <button class="btn" wire:loading.attr="disabled" wire:target="create">
                    <span>Submit</span>
                    <x-phosphor.icons::bold.circle-notch
                        class="size-4 animate-spin"
                        wire:loading.inline-block
                        wire:target="create"
                    />
                </button>
            </form>
        </dialog>
        @endpersist
    </x-outlet>
</div>
