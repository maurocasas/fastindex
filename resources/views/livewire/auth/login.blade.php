<form wire:submit.prevent="attempt" class="space-y-4 w-80">
    <div class="space-y-2 flex flex-col">
        <label class="label" for="email">Email</label>
        <input id="email" name="email" type="text" class="input" placeholder="Email" autofocus
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
    <button class="btn" wire:loading.attr="disabled" wire:target="attempt">
        <span>Login</span>
        <x-phosphor.icons::bold.circle-notch
            class="size-4 animate-spin"
            wire:loading.inline-block
            wire:target="attempt"
        />
    </button>
</form>
