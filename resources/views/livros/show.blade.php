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
                    <p class="text-sm text-gray-600 text-opacity-50"><span class="font-bold">{{ $reqs }}</span> de <span
                            class="font-bold">{{ $livro->stock - (auth()->user()?->cart ? auth()->user()->cart?->items->sum('amount') ?? 0 : 0) }}</span>
                        requisitados</p>
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



                    @if (session('sucesso'))
                        <div class="alert alert-success">
                            <ul>
                                <li>{{ session()->pull('sucesso') }}</li>
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

                        @if($livro->requisicoes()->where('status', 'ativa')->exists())
                            <form action="{{ route('notificar.disponivel', $livro) }}" method="POST">
                                @csrf
                                <button class="btn btn-primary">Notificar-me quando disponível</button>
                            </form>
                        @endif

                        @if (($livro->stock - (auth()->user()?->cart ? auth()->user()->cart->items->sum('amount') : 0) - $reqs) > 0)
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
        @if ($reqs_with_reviews->count() > 0)
            <div class="container flex flex-col gap-3 px-20 pt-5 mx-auto">
                <h3>Reviews</h3>
                @foreach ($reqs_with_reviews as $req)
                    <div class="flex flex-col gap-1 w-full bg-orange-400 rounded-lg p-5">
                        <div class="w-fit">
                            {{ $req->review->user->name }}
                        </div>
                        <hr class="border-orange-900">
                        <div class="flex flex-row">
                            <div class="grow">
                                {{ $req->review->comentario }}
                            </div>
                            <div class="w-1/12">
                                {{ $req->review->pontuacao }} / 10
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="w-3/4 mx-auto mt-5">
            <h1 class="font-bold text-lg mb-3">Livros semelhantes:</h1>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-2">
                @foreach($recomendacoes as $rec)
                            <div class="card bg-orange-400 shadow-xl">
                                <figure class="px-4 pt-4">
                                    <img src="{{ $rec->capa
                    ? asset('storage/' . $rec->capa)
                    : 'https://via.placeholder.com/300x400?text=Sem+Capa' }}" alt="{{ $rec->nome }}"
                                        class="rounded-lg object-cover h-64 w-full" />
                                </figure>
                                <div class="card-body">
                                    <h2 class="card-title">{{ $rec->nome }}</h2>
                                    @if($rec->autores->isNotEmpty())
                                        <p class="text-sm text-gray-600">
                                            {{ $rec->autores->pluck('nome')->join(', ') }}
                                        </p>
                                    @endif
                                    <p class="text-sm text-gray-600">
                                        Editora: {{ $rec->editoras->nome }}
                                    </p>
                                    <div class="card-actions justify-between items-center mt-4">
                                        <span class="badge badge-primary text-lg">
                                            €{{ number_format($rec->preco, 2, ',', '.') }}
                                        </span>
                                        <a href="{{ route('livros.show', $rec) }}" class="btn btn-sm btn-primary">
                                            Ver
                                        </a>
                                    </div>
                                </div>
                            </div>
                @endforeach
            </div>
        </div>
    </div>
</x-guest-layout>