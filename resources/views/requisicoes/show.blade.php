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

                    @if (auth()->user()?->isAdmin() && $requisicao->status === "ativa")
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


                    @if (auth()->user()?->isCidadao() && $requisicao->status === "entregue")
                        @if (!$requisicao->review)

                            <div class="card-actions mt-5">
                                <h3>Envie a sua review aqui:</h3>
                                <form method="post" action="{{ route('reviews.store') }}"
                                    class="flex flex-col justify-between gap-3 w-full">
                                    @csrf
                                    <input type="hidden" name="requisicao_id" value="{{ $requisicao->id }}">
                                    <label for="pontuacao">
                                        Pontuação
                                        <input type="number" name="pontuacao" min="0" max="10" required
                                            class="ms-3 input input-md bg-orange-100 border border-base-100"
                                            placeholder="Escreva um numero entre 0 e 10">
                                    </label>
                                    <textarea name="comentario"
                                        class="textarea textarea-ghost w-full focus:bg-orange-100 focus:text-black"
                                        placeholder="Escreva o que acho"></textarea>
                                    <button type="submit" class="btn btn-primary">Enviar review</button>
                                </form>
                            </div>
                        @else
                            <h3>Review já submitida</h3>
                        @endif
                    @endif
                </div>
            </div>
        </li>
    </ul>
</x-guest-layout>