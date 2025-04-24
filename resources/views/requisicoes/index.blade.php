<x-guest-layout>

    @if (auth()->user()?->isAdmin())
        <div class="container-full flex mt-6">
            <span class="flex-grow text-center">
                Requisições ativas: {{ $ativas }}
            </span>
            <span class="flex-grow text-center">
                Últimos 30 dias: {{ $ult30dias }}
            </span>
            <span class="flex-grow text-center">
                Entregues hoje: {{ $entreguesHoje }}
            </span>
        </div>
    @endif

    <ul role="list">
        @foreach ($reqs as $requisicao)

            <li class="py-5">
                <div class="card w-5/6 bg-orange-300 card-md shadow-sm mx-auto">
                    <div class="card-body">
                        <h2 class="card-title">
                            Requisiçao: {{ $requisicao->numero }}
                        </h2>
                        <hr class="border-orange-900">
                        <p>
                            {{ $requisicao->livro->nome }}
                        </p>
                        <p>
                            <span class="font-bold">Estado:</span> {{ $requisicao->status }}
                        </p>
                        <p>
                            <span class="font-bold">Data de início:</span> {{ $requisicao->data_inicio }}
                        </p>
                        <p>
                            <span class="font-bold">Data de entrega prevista:</span> {{ $requisicao->data_prevista_fim }}
                        </p>
                        <p>
                            <span class="font-bold">Data de entrega real:</span> {{ $requisicao->data_real_fim }}
                        </p>

                        <div class="justify-end card-actions">
                            <a href="{{ route('requisicoes.show', $requisicao) }}" class="btn btn-primary">
                                Ver requisição
                            </a>
                        </div>
                    </div>
                </div>
            </li>
        @endforeach

    </ul>
</x-guest-layout>