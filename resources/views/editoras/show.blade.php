<x-guest-layout>
    <div class="min-h-screen py-16">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold mb-4">{{ $editora->nome }}</h1>
            <p class="mb-6">{{ $editora->descricao ?? 'Sem descrição disponível.' }}</p>

            <h2 class="text-2xl font-semibold mb-4">Livros Publicados</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($editora->livros as $livro)
                <div class="card bg-orange-400 shadow-xl">
                    <figure class="px-4 pt-4">
                        <img
                            src="{{ $livro->capa
                  ? asset('storage/'.$livro->capa)
                  : 'https://via.placeholder.com/300x400?text=Sem+Capa' }}"
                            alt="{{ $livro->nome }}"
                            class="rounded-lg object-cover h-64 w-full" />
                    </figure>
                    <div class="card-body">
                        <h3 class="card-title">{{ $livro->nome }}</h3>
                        <div class="card-actions justify-between items-center mt-4">
                            <span class="badge badge-secondary">
                                €{{ number_format($livro->preco,2,',','.') }}
                            </span>
                            <a href="{{ route('livros.show', $livro) }}"
                                class="btn btn-sm btn-primary">
                                Ver
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8 flex justify-center">
                {{ $editora->livros->isNotEmpty() ? '' : '— Nenhum livro publicado —' }}
            </div>
        </div>
    </div>
</x-guest-layout>