<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- Styles -->
  @livewireStyles
</head>

<body class="min-h-screen bg-orange-100">
  <nav class="bg-orange-900">
    <div class="flex items-center px-6 justify-between">
      <div class="flex items-center space-x-10">
        <div class="p-3 rounded-full bg-amber-100 flex items-center justify-items-center m-3">
          <img class="w-16 h-16" src="{{ asset('images/logo.png') }}" alt="My Logo">
        </div>

        @if (!(request()->is("user/confirm-password") || request()->is("two-factor-challenge") || request()->is("forgot-password")))

        <x-nav-link href="/" :active="request()->is('/')">
          Home
        </x-nav-link>
        <x-nav-link href="/livros" :active="request()->is('livros')">
          Livros
        </x-nav-link>
        <x-nav-link href="/autores" :active="request()->is('autores')">
          Autores
        </x-nav-link>
        <x-nav-link href="/editoras" :active="request()->is('editoras')">
          Editoras
        </x-nav-link>

        @auth
        <x-nav-link href="/requisicoes" :active="request()->is('requisicoes')">
        Requisições
        </x-nav-link>
      @endauth

    @endif
      </div>

      @auth
      <div class="ms-auto me-10">
        @if (auth()->user()->cart || auth()->user()->isAdmin())
          <a href="{{ route('cart.index') }}" class="relative">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
              <path fill-rule="evenodd"
                d="M4 4a1 1 0 0 1 1-1h1.5a1 1 0 0 1 .979.796L7.939 6H19a1 1 0 0 1 .979 1.204l-1.25 6a1 1 0 0 1-.979.796H9.605l.208 1H17a3 3 0 1 1-2.83 2h-2.34a3 3 0 1 1-4.009-1.76L5.686 5H5a1 1 0 0 1-1-1Z"
                clip-rule="evenodd" />
            </svg>
            @if (auth()->user()->cart)
              <div class="absolute size-6 bg-red-500 rounded-full flex items-center -right-6 -bottom-4">
                <span class="text-xs font-mono mx-auto">{{ auth()->user()->cart->items->sum('amount') ?? 0 }}</spa>
              </div>
            @endif
          </a>
        @endif
      </div>
    @endauth

      @if (!(request()->is("user/confirm-password") || request()->is("two-factor-challenge")))
      <div class="flex items-center space-x-2">
      @guest
      <x-nav-link href="/login" class="btn btn-primary">
      Login
      </x-nav-link>

      <x-nav-link href="/register" class="btn btn-primary">
      Registar
      </x-nav-link>
    @endguest

      @auth
      <div class="dropdown dropdown-end">
        <div tabindex="0" role="button" class="btn btn-ghost rounded-field">{{ auth()->user()->name }}</div>
        <ul tabindex="0"
        class="dropdown-content bg-orange-400 rounded-box z-1 mt-4 p-2 shadow-sm flex flex-col gap-3">
        <li>
        <form method="post" action="/user/two-factor-authentication">
          @csrf

          @if (auth()->user()->two_factor_secret)

          @method("DELETE")

          <button type="submit" class="btn btn-error">Disable 2FA</button>

          @else

            <button type="submit" class="btn btn-primary">Enable 2FA</button>

          @endif

        </form>
        </li>
        @if (auth()->user()->two_factor_secret)
        <li>
        {!! auth()->user()->twoFactorQrCodeSvg() !!}
        </li>
        <li>
        <h6>Recovery code:</h6>
        @php
        $codes = json_decode(decrypt(auth()->user()->two_factor_recovery_codes))
        @endphp
        <div class="bg-base-200 p-2 rounded-field w-fit text-xs font-bold font-mono">
        {{ $codes[array_rand($codes)] }}
        </div>
        </li>
      @endif
        <li>
        <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-error">Logout</button>
        </form>
        </li>
        </ul>
      </div>
    @endauth
      </div>
    @endif
    </div>
  </nav>


  <div class="font-sans text-gray-900 antialiased">
    {{ $slot }}
  </div>

  @livewireScripts
</body>

</html>