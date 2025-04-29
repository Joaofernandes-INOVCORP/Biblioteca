<x-guest-layout>
    <div class="min-h-screen py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Montra de Livros</h2>

            <div class="my-3 flex flex-row">
                <form action="" method="get" class="flex flex-wrap flex-grow gap-3 pe-3">
                    <label class="floating-label w-full">
                        <input type="text" placeholder="Search"
                            class="input input-md bg-orange-100 border border-base-100 w-full" name="search" />
                        <span class="text-white">Search</span>
                    </label>


                    <label class="floating-label">
                        <input type="number" min="0" step="0.01" placeholder="Preço desde"
                            class="input input-md bg-orange-100 border border-base-100" name="price_min" />
                        <span class="text-white">Preço desde</span>
                    </label>


                    <label class="floating-label">
                        <input type="number" min="0" step="0.01" placeholder="Preço até"
                            class="input input-md bg-orange-100 border border-base-100" name="price_max" />
                        <span class="text-white">Preço até</span>
                    </label>

                    <select class="select bg-orange-100 border border-base-100" name="order">
                        <option selected value="asc">Order ascendente</option>
                        <option value="desc">Order descendente</option>
                    </select>

                    <button type="submit" class="btn btn-primary">Search</button>

                </form>

                @if (auth()->user()?->isAdmin())
                    <div class="flex flex-wrap flex-grow gap-3 ps-3">
                        <a href="/livros/create" class="btn btn-primary">Registar Livro</a>
                        <a href="/livros/export" class="btn btn-primary">Exportar Livros</a>
                    </div>
                @endif

            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($livros as $livro)
                            <div class="card bg-orange-400 shadow-xl">
                                <figure class="px-4 pt-4">
                                    <img src="{{ $livro->capa
                    ? asset('storage/' . $livro->capa)
                    : 'https://via.placeholder.com/300x400?text=Sem+Capa' }}" alt="{{ $livro->nome }}"
                                        class="rounded-lg object-cover h-64 w-full" />
                                </figure>
                                <div class="card-body">
                                    <h2 class="card-title">{{ $livro->nome }}</h2>
                                    @if($livro->autores->isNotEmpty())
                                        <p class="text-sm text-gray-600">
                                            {{ $livro->autores->pluck('nome')->join(', ') }}
                                        </p>
                                    @endif
                                    <p class="text-sm text-gray-600">
                                        Editora: {{ $livro->editoras->nome }}
                                    </p>
                                    <div class="card-actions justify-between items-center mt-4">
                                        <span class="badge badge-primary text-lg">
                                            €{{ number_format($livro->preco, 2, ',', '.') }}
                                        </span>
                                        <a href="{{ route('livros.show', $livro) }}" class="btn btn-sm btn-primary">
                                            Ver
                                        </a>
                                    </div>
                                </div>
                            </div>
                @endforeach
            </div>

            <div class="mt-8 flex justify-center">
                {{ $livros->links() }}
            </div>
        </div>
    </div>
</x-guest-layout>