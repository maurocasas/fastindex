<div x-on:close-modal="document.getElementById('new_service_account').close();" wire:poll>
    <x-outlet>
        <x-slot:title>
            Service Accounts
        </x-slot:title>
        <x-slot:actions>
            <button type="button" class="btn"
                    x-on:click="document.getElementById('new_service_account').showModal();">
                <x-phosphor.icons::bold.asterisk class="size-4"/>
                <span>Link service account</span>
            </button>
        </x-slot:actions>
        @persist('modal')
        <dialog id="new_service_account" class="p-8 space-y-8 rounded-lg md:rounded-xl w-full max-w-md"
                wire:ignore.self>
            <div class="flex items-center justify-between">
                <h3 class="font-medium text-lg">Link service account</h3>
                <button type="button" x-on:click="document.getElementById('new_service_account').close();">
                    <x-phosphor.icons::bold.x class="size-4"/>
                </button>
            </div>
            <form wire:submit.prevent="store" class="space-y-8">
                <div class="space-y-2 flex flex-col max-w-sm">
                    <label class="label" for="name">
                        Select <b>credentials.json</b>*
                    </label>
                    <input type="file" id="credentials" wire:model="credentials" required accept="application/json"
                           class="input"/>
                    @error('credentials')
                    <div class="validation-error">{{ $message }}</div>
                    @enderror
                    <div class="help-text">
                        <a href="#" class="link">Learn how to create a service account</a>
                    </div>
                </div>

                <button type="submit" class="btn" wire:loading.attr="disabled" wire:target="store">
                    <span>Link service account</span>
                    <x-phosphor.icons::bold.circle-notch class="size-4 animate-spin" wire:loading.inline-block
                                                         wire:target="store"/>
                </button>
            </form>
        </dialog>
        @endpersist
        @if($risk > 0)
            <div class="bg-red-50 border border-red-100 text-red-500 p-4 rounded-lg md:rounded-xl text-sm">
                You're at risk of getting banned by <b>Google Search Console</b>. You have {{$risk}} site(s) with more
                than one service account, this violates GSC Terms & Conditions.
            </div>
        @endif
        @if($serviceAccounts->isEmpty())
            <div class="p-8 text-slate-600 rounded text-center space-y-4">
                <h2 class="text-xl font-medium">No service accounts linked</h2>
                <p>
                    Service accounts bridge FastIndex to your Google Search Console.
                </p>
                <button type="button" class="btn"
                        x-on:click="document.getElementById('new_service_account').showModal();">
                    <x-phosphor.icons::bold.asterisk class="size-4"/>
                    <span>Link service account</span>
                </button>
            </div>
        @else
            <div class=" border bg-white rounded-lg shadow-sm border-slate-100">
                <table class="w-full text-sm">
                    <thead class="text-left border-b border-slate-100">
                    <tr class="*:py-3">
                        <th class="pl-3 w-[50%]">Email</th>
                        <th class="w-[20%]">Sites</th>
                        <th class="w-[20%]">Activity (Last 24h)</th>
                        <th class="w-[10%]">&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($serviceAccounts as $serviceAccount)
                        <tr class="odd:bg-slate-100/20 *:py-2" wire:key="service_account_{{$serviceAccount->id}}">
                            <td class="pl-3">
                                {{$serviceAccount->credentials['client_email']}}
                            </td>
                            <td>
                                {{$serviceAccount->sites_count}}
                            </td>
                            <td>
                                {{$serviceAccount->logs_count}}
                            </td>
                            <td class="pr-2 text-right">
                                <button type="button" class="btn btn-outline !text-red-500"
                                        wire:click="destroy({{$serviceAccount->id}})"
                                        wire:confirm="Are you sure you want to delete this service account?">
                                    <span>Delete</span>
                                    <x-phosphor.icons::bold.trash class="size-4"/>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div>
                {{$serviceAccounts->links()}}
            </div>
        @endif
    </x-outlet>
</div>
