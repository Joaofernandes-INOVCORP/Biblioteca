<x-guest-layout>
    <ul role="list">
        @foreach ($reviews as $review)

            <li class="py-5">
                <div class="card w-5/6 bg-orange-300 card-md shadow-sm mx-auto">
                    <div class="card-body">
                        <h2 class="card-title">
                            Livro: {{ $review->requisicao->livro->nome }}
                        </h2>
                        <p class="text-xs text-opacity-50">
                            Review de {{ $review->user->name }}
                        </p>
                        <p class="font-bold opacity-50">
                            {{ $review->estado }}
                        </p>
                        <hr class="border-orange-900">
                        <p>
                            <span class="font-bold">Pontuação:</span> {{ $review->pontuacao }} / 10
                        </p>
                        <p>
                            <span class="font-bold">Comentário:</span> {{ $review->comentario }}
                        </p>

                        <div class="justify-end card-actions">
                            <a href="{{ route('reviews.show', $review) }}" class="btn btn-primary">
                                Ver review
                            </a>
                        </div>
                    </div>
                </div>
            </li>
        @endforeach

    </ul>
</x-guest-layout>