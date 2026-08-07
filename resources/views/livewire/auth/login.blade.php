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
            class="mx-auto w-20 mb-4">

        <h1 class="text-3xl font-bold text-[#9F0712]">
            Premiere Medical
        </h1>

        <p class="text-gray-500">
            Corrective & Preventive Action Report System
        </p>

    </div>

    <x-auth-session-status
        :status="session('status')" />

    <form
        wire:submit.prevent="login"
        class="space-y-5">

        <flux:input
            wire:model="username"
            label="Username" />

        <flux:input
            wire:model="password"
            type="password"
            label="Password" />

        <div class="flex justify-between items-center">

            <flux:checkbox
                wire:model="remember"
                label="Remember me" />

            <flux:link href="{{ route('password.request') }}">
                Forgot password?
            </flux:link>

        </div>

        <flux:button
            class="w-full bg-[#9F0712]">
            Log in
        </flux:button>

    </form>

</div>