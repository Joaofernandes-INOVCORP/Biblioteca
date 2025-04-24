<x-guest-layout>


    <ul role="list">
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


                    @if (auth()->user()?->isAdmin())
                        <p>
                            <span class="font-bold">Requisitado por:</span> {{ $requisicao->user->name }}
                        </p>
                    @endif

                    @if (auth()->user()?->isAdmin())
                        <div class="justify-end card-actions">
                            <form method="post">
                                @csrf
                                @method("PUT")
                                <input type="hidden" name="action" value="finish">
                                <button type="submit" class="btn btn-success">Confirmar entrega</button>
                            </form>
                            <form method="post">
                                @csrf
                                @method("PUT")
                                <input type="hidden" name="action" value="extend">
                                <button type="submit" class="btn btn-primary">Extender 5 dias</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </li>

    </ul>
</x-guest-layout>