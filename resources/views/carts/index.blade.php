<x-guest-layout>
    <div class="min-h-screen py-16">
        <div class="container mx-auto px-4">
            <ul role="list">
                @foreach ($carts as $cart)
                    <li class="py-5">
                        <div class="card w-5/6 bg-orange-300 card-md shadow-sm mx-auto">
                            <div class="card-body">
                                @if (auth()->user()?->isAdmin())
                                <p class="text-xs text-opacity-50">
                                    Carrinho de {{ $cart->user->name }}
                                </p>
                                <hr class="border-orange-900">
                                @endif
                                
                                @foreach ($cart->items as $item)
                                <div class="flex flex-row justify-between gap-3 items-center">
                                    <div class=" w-1/12">
                                        <img src="{{ $item->livro->capa ? asset('storage/' . $item->livro->capa) : 'https://via.placeholder.com/300x400?text=Sem+Capa' }}"
                                        alt="{{ $item->livro->nome }}" class="block aspect-square w-full">
                                        
                                    </div>
                                    <div class=" flex-grow">
                                        {{ $item->livro->nome }}
                                    </div>
                                    <div class=" w-1/12">
                                        x {{ $item->amount }}
                                    </div>
                                </div>
                                <hr class="border-orange-900">
                                @endforeach

                                <p class="text-base text-opacity-50 text-right px-8">
                                    Total:
                                    <span class="font-bold">
                                        {{ $cart->items->sum(fn($item) => $item->amount * $item->livro->preco) }} €
                                    </span>
                                </p>

                                <div class="justify-end card-actions">
                                    <a href="" class="btn btn-primary">
                                        Ver requisição
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</x-guest-layout>