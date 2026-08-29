<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-[#9F0712]">
    <div class="min-h-screen bg-[#9F0712] flex items-center justify-center p-8">

        <div class="w-full max-w-7xl h-[85vh] bg-white rounded-[40px] overflow-hidden shadow-2xl">

            <div class="grid lg:grid-cols-2 h-full">

                {{-- LEFT --}}
                <div class="flex items-center justify-center p-16">

                    <div class="w-full max-w-md">
                        {{ $slot }}
                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="relative hidden lg:block">

                    <img
                        src="{{ asset('login/Secure login-rafiki.png') }}"
                        class="absolute inset-0 w-full h-full object-cover"
                        alt="">

                </div>

            </div>

        </div>

    </div>
    @fluxScripts
</body>

</html>