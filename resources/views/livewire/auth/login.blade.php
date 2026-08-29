<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string')]
    public string $username = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['username' => $this->username, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'username' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirectIntended(default: route('user_dashboard', absolute: false), navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }
    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->username) . '|' . request()->ip());
    }
}; ?>

<div class="space-y-8">

    <div class="text-center">

        <img
            src="{{ asset('logo/premiere_logo.png') }}"
            class="mx-auto w-20 mb-4"
            alt="Premiere Logo">

        <h1 class="text-1xl font-bold text-[#9F0712] leading-tight">
            Premiere Medical & Cardiovascular Laboratory, Inc.
        </h1>

    </div>

    <x-auth-session-status
        :status="session('status')" />

    <form wire:submit.prevent="login" class="space-y-5">

        <flux:input
            wire:model="username"
            label="Username"
            class="
        !bg-white dark:!bg-zinc-900
        !text-zinc-900 dark:!text-white
        !border-zinc-300 dark:!border-zinc-700
        placeholder:!text-zinc-400 dark:placeholder:!text-zinc-500
        focus:!border-[#9F0712]
        focus:!ring-[#9F0712]/20
        " />

        <flux:input
            wire:model="password"
            type="password"
            label="Password"
            class="
        !bg-white dark:!bg-zinc-900
        !text-zinc-900 dark:!text-white
        !border-zinc-300 dark:!border-zinc-700
        placeholder:!text-zinc-400 dark:placeholder:!text-zinc-500
        focus:!border-[#9F0712]
        focus:!ring-[#9F0712]/20
        " />

        <div class="flex items-center justify-between">

            <flux:checkbox
                wire:model="remember"
                label="Remember me"
                class="text-sm" />

            <flux:link
                href="{{ route('password.request') }}"
                class="text-sm">
                Forgot password?
            </flux:link>

        </div>

        <flux:button
            class="w-full bg-[#9F0712] hover:bg-[#7F0510] text-white"
            type="submit">
            Log in
        </flux:button>
        <p class="text-center text-xs text-zinc-400">
            Corrective Preventive Action Report System © 2026
        </p>
    </form>
</div>