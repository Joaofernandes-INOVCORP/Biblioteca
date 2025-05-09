<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST">
            @csrf

            <div class="form-control">
            <x-label for="name" value="{{ __('ISBN') }}" />
                <div class="flex gap-2">
                <x-input id="name" class="block mt-1 w-full" type="text" name="isbn" :value="$livro['isbn'] ?? old('ispn')" required autofocus autocomplete="name" />
                    <button type="submit" id="buscar-isbn" class="btn btn-secondary">
                        Pesquisar
                    </button>
                </div>
                @if (array_key_exists("error", $livro))
                    <p id="isbn-erro" class="text-sm text-red-600">
                        {{ $livro["error"] }}
                    </p>
                @endif
            </div>
        </form>
        
        <p class="my-4 font-bold text-gray-300">
            @if (array_key_exists('titulo',$livro	))
                Confirme os dados do livro:
                @else
                Se preferir pode inserir manualmente os dados do livro que pretende adicionar:
            @endif
        </p>

        <form method="POST" action="/livros" enctype="multipart/form-data">
            @csrf
            <div>
                <x-label for="name" value="{{ __('ISBN') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="isbn" :value="$livro['isbn'] ?? old('ispn')" required
                    autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-label for="titulo" value="{{ __('Titulo') }}" />
                <x-input id="titulo" class="block mt-1 w-full" type="text" name="titulo" :value="$livro['titulo'] ?? old('titulo')" required
                    autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="bibliografia" value="{{ __('Bibliografia') }}" />
                <x-input id="bibliografia" class="block mt-1 w-full" type="text" name="bibliografia"
                    :value="$livro['bibliografia'] ?? old('bibliografia')" required autocomplete="bibliografia" />
            </div>

            <div class="mt-4">
                <x-label for="preco" value="{{ __('Preço') }}" />
                <x-input id="preco" class="block mt-1 w-full" type="number" min="0" step="0.01" name="preco"
                    :value="$livro['preco'] ?? old('preco')" required autocomplete="preco" />
            </div>

            <div class="mt-4">
                <x-label for="editora" value="{{ __('Editora') }}" />
                <x-input id="editora" class="block mt-1 w-full" type="text" name="editora" :value="$livro['editora'] ?? old('editora')"
                    required autocomplete="editora" />
            </div>

            <div class="mt-4">
                <x-label for="stock" value="{{ __('Stock') }}" />
                <x-input id="stock" class="block mt-1 w-full" type="number" min="0" step="1" name="stock" required/>
            </div>

            <div class="mt-4">
                <x-label for="capa" value="{{ __('Capa') }}" />
                <x-input id="capa" class="block mt-1 w-full" type="file" name="capa" autocomplete="capa" />
            </div>


            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="mt-4">
                            <x-label for="terms">
                                <div class="flex items-center">
                                    <x-checkbox name="terms" id="terms" required />

                                    <div class="ms-2">
                                        {!! __('I agree to the :terms_of_service and :privacy_policy', [
                    'terms_of_service' => '<a target="_blank" href="' . route('terms.show') . '" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">' . __('Terms of Service') . '</a>',
                    'privacy_policy' => '<a target="_blank" href="' . route('policy.show') . '" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">' . __('Privacy Policy') . '</a>',
                ]) !!}
                                    </div>
                                </div>
                            </x-label>
                        </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <x-button class="ms-4">
                    {{ __('Registar') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>