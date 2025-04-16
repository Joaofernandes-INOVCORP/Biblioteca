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

<body>
    <nav class="bg-gray-800">
        <div class="flex items-center h-16 px-6 justify-between">
            <div class="flex items-center space-x-6">
                <img class="size-8" src="https://laracasts.com/images/logo/logo-triangle.svg" alt="Logo">
                <x-nav-link href="/" :active="request()->is('/')">Home</x-nav-link>
                <x-nav-link href="/livros" :active="request()->is('livros')">Livros</x-nav-link>
                <x-nav-link href="/autores" :active="request()->is('autores')">Autores</x-nav-link>
                <x-nav-link href="/editoras" :active="request()->is('editoras')">Editoras</x-nav-link>
            </div>
            <div class="flex items-center space-x-2">
    @guest
        <x-nav-link href="/login">Login</x-nav-link>
    @endguest

    @auth
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-white hover:underline">Logout</button>
        </form>
    @endauth
</div>

        </div>
    </nav>

    <div class="font-sans text-gray-900 dark:text-gray-100 antialiased">
        {{ $slot }}
    </div>

    @livewireScripts
</body>

</html>