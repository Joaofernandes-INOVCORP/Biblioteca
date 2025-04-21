<x-guest-layout>
    <div class="min-h-screen py-16">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold mb-4">{{ $autor->nome }}</h1>
            <p class="mb-6">{{ $autor->biografia ?? 'Sem biografia disponível.' }}</p>

            <h2 class="text-2xl font-semibold mb-4">Livros deste Autor</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($autor->livros as $livro)
                @include('livros._card', ['livro' => $livro])
                @endforeach
            </div>
        </div>
    </div>
</x-guest-layout>