<x-guest-layout>
    <ul role="list">
        <li class="py-5">
            <div class="card w-5/6 bg-orange-300 card-md shadow-sm mx-auto">
                <div class="card-body">
                    <h2 class="card-title">
                        Livro: {{ $review->requisicao->livro->nome }}
                    </h2>
                    <p class="text-xs text-opacity-50">
                        Review de {{ $review->user->name }}
                    </p>
                    <hr class="border-orange-900">
                    <p>
                        <span class="font-bold">Pontuação:</span> {{ $review->pontuacao }} / 10
                    </p>
                    <p>
                        <span class="font-bold">Comentário:</span> {{ $review->comentario }}
                    </p>

                    @if ($review->estado !== 'suspenso')
                        <p>
                            <span class="font-bold">estado:</span> {{ $review->estado }}
                        </p>
                        @if ($review->estado == 'recusado')
                        <p>
                            <span class="font-bold">Razão:</span> {{ $review->razao }}
                        </p>
                        @endif
                    @else
                        <div class="card-actions w-full">
                            <form action="{{ route('reviews.update', $review) }}" method="post" class="w-full">
                                @csrf
                                @method('PUT')
                                <textarea name="justificacao"
                                    class="textarea textarea-ghost w-full focus:bg-orange-100 focus:text-black"
                                    placeholder="Escreva aqui a justificação se decidir rejeitar esta review..."></textarea>
                                <div class="w-full flex flex-row justify-end gap-2 py-5">
                                    <button name="estado" value="ativo" class="btn btn-success" type="submit">
                                        Aprovar
                                    </button>
                                    <button name="estado" value="recusado" class="btn btn-error" type="submit">
                                        Rejeitar
                                    </button>
                                </div>
                            </form>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-error">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </li>
    </ul>
</x-guest-layout>