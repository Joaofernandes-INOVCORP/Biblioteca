<x-guest-layout>
    <div class="min-h-screen py-16">
        <div class="container mx-auto px-4">
            <div class="card lg:card-side bg-orange-400 shadow-xl">
                <figure class="p-4 w-80 flex-none">
                    <img src="{{ $livro->capa
    ? asset('storage/' . $livro->capa)
    : 'https://via.placeholder.com/300x400?text=Sem+Capa' }}" alt="{{ $livro->nome }}"
                        class="rounded-lg w-auto h-auto block" />
                </figure>
                <div class="card-body">
                    <h1 class="card-title text-3xl">{{ $livro->nome }}</h1>
                    <p class="text-sm text-gray-600 text-opacity-50"><span class="font-bold">{{ $reqs }}</span> de <span class="font-bold">{{ $livro->stock - (auth()->user()?->cart? auth()->user()->cart?->items->sum('amount') ?? 0 : 0) }}</span> requisitados</p>
                    <p class="text-sm text-gray-600">
                        ISBN: {{ $livro->isbn }}
                    </p>
                    <p class="text-sm text-gray-600">
                        Editora: {{ $livro->editoras->nome }}
                    </p>
                    @if($livro->autores->isNotEmpty())
                        <p class="text-sm text-gray-600">
                            Autor{{ $livro->autores->count() > 1 ? 'es' : '' }}:
                            {{ $livro->autores->pluck('nome')->join(', ') }}
                        </p>
                    @endif
                    <p class="mt-4">{{ $livro->bibliografia }}</p>

                    @if ($errors->any())
                        <div class="alert alert-error">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mt-6 flex items-center space-x-4">
                        <span class="badge badge-primary text-xl">
                            €{{ number_format($livro->preco, 2, ',', '.') }}
                        </span>

                        <a href="{{ route('livros.index') }}" class="btn btn-ghost">
                            Voltar
                        </a>

                        @if ($reqs < $livro->stock)
                            <form method="POST" action="/requisicoes">
                                @csrf
                                <input type="hidden" name="livro_id" value="{{ $livro->id }}">
                                <button type="submit" class="btn btn-primary">Requisitar</button>
                            </form>
                        @endif

                        @if (($livro->stock - (auth()->user()?->cart? auth()->user()->cart->items->sum('amount') : 0) - $reqs) > 0)
                            <form method="POST" action="{{ route("cart.store") }}">
                                @csrf
                                <input type="hidden" name="livro_id" value="{{ $livro->id }}">
                                <input type="hidden" name="amount" value="1">
                                <button type="submit" class="btn btn-primary">Adicionar ao carrinho</button>
                            </form>
                        @endif

                        <form method="POST" action="">
                            @csrf
                            @method("DELETE")
                            <button type="submit" class="btn btn-error">Eliminar</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>