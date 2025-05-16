<x-guest-layout>
    <div class="min-h-screen py-16">
        <div class="container mx-auto px-4">
            @if ($cart)
                <ul role="list">
                    <li class="py-5">
                        <div class="card w-5/6 bg-orange-300 card-md shadow-sm mx-auto">
                            <div class="card-body">
                                @foreach ($cart->items as $item)
                                    <div class="flex flex-row justify-between gap-3 items-center">
                                        <div class=" w-1/12">
                                            <img src="{{ $item->livro->capa ? asset('storage/' . $item->livro->capa) : 'https://via.placeholder.com/300x400?text=Sem+Capa' }}"
                                                alt="{{ $item->livro->nome }}" class="block aspect-square w-full">

                                        </div>
                                        <div class=" flex-grow">
                                            {{ $item->livro->nome }}
                                        </div>
                                        <div class="w-4/12 flex flex-row items-center">
                                            <span class="w-1/2">{{ $item->livro->preco }} x</span>
                                            <form class="flex flex-row gap-3 flex-grow" method="post"
                                                action="{{ route('cart.update', auth()->user()->cart) }}">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="livro_id" value="{{ $item->livro->id }}">
                                                <input type="number" min="0" name="amount" value="{{ $item->amount }}"
                                                    class="flex-grow rounded-lg">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </form>
                                        </div>
                                    </div>
                                    <hr class="border-orange-900">
                                @endforeach

                                @if ($errors->any())
                                    <div class="alert alert-error">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                
                                <p class="text-base text-opacity-50 text-right px-8">
                                    Total:
                                    <span class="font-bold">
                                        {{ $cart->items->sum(fn($item) => $item->amount * $item->livro->preco) }} €
                                    </span>
                                </p>
                                <div class="justify-end card-actions mt-10">
                                    <form action="{{ route('checkout') }}" method="post" class="w-1/3 flex flex-col gap-1">
                                        @csrf
                                        <label for="morada">Morada</label>
                                        <input class="w-full rounded-lg" type="text" name="morada" required />

                                        <button type="submit" class="btn btn-primary">Finalizar compra</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            @endif
            @if ($enc)
                <ul role="list">
                    @foreach ($enc as $e)
                        <li class="py-5">
                            <div class="card w-5/6 bg-orange-300 card-md shadow-sm mx-auto">
                                <div class="card-body">
                                    @if (auth()->user()?->isAdmin())
                                        <p class="text-base">
                                            Ecomenda de {{ $e->user->name }}
                                        </p>
                                        <p class="text-xs text-opacity-50">
                                            {{ $e->estado }}
                                        </p>
                                        <hr class="border-orange-900">
                                    @endif

                                    @foreach ($e->items as $item)
                                        <div class="flex flex-row justify-between gap-3 items-center">
                                            <div class=" w-1/12">
                                                <img src="{{ $item->livro->capa ? asset('storage/' . $item->livro->capa) : 'https://via.placeholder.com/300x400?text=Sem+Capa' }}"
                                                    alt="{{ $item->livro->nome }}" class="block aspect-square w-full">

                                            </div>
                                            <div class=" flex-grow">
                                                {{ $item->livro->nome }}
                                            </div>
                                            <div class=" w-1/12">
                                                {{ $item->preco }} x {{ $item->quantidade }}
                                            </div>
                                        </div>
                                        <hr class="border-orange-900">
                                    @endforeach

                                    <p class="text-base text-opacity-50 text-right px-8">
                                        Total:
                                        <span class="font-bold">
                                            {{ $e->total }} €
                                        </span>
                                    </p>

                                    <div class="justify-end card-actions mt-10">
                                        @if ($e->estado == 'pendente')

                                            <form action="{{ route('encomenda.update') }}" method="post"
                                                class="flex flex-col gap-1 w-1/3">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $e->id }}">
                                                <button type="submit" class="btn btn-primary">Enviar encomenda</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-guest-layout>