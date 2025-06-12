<x-guest-layout>

    <div class="w-3/4 mx-auto my-5">
        <form action="{{ route('chatrooms.store') }}" method="post" class="flex flex-col gap-6 items-end">
            @csrf

            <label class="floating-label w-full">
                <input type="text" placeholder="Nome" pattern="[A-Za-z0-9]{3,10}"
                    class="input input-md bg-orange-100 border border-base-100 w-full" name="nome" required />
                <span class="text-white">Nome</span>
            </label>

            <label class="floating-label w-full">
                <input type="file" class="input input-md bg-orange-100 border border-transparent w-full"
                    name="avatar" />
                <span class="text-white">Avatar</span>
            </label>

            <h2 class="w-full font-bold">Participantes da sala:</h2>
            <div class="flex flex-row gap-3 flex-wrap">
                @foreach ($utilizadores as $u)
                    <label class="flex items-center px-4 border border-orange-900 rounded-sm w-fit">
                        <input type="checkbox" name="utilizadores[]"
                            class="w-4 h-4 text-orange-600 bg-orange-100 border-orange-950 rounded-sm focus:ring-blue-500 focus:ring-2"
                            value="{{ $u->id }}">
                        <span
                            class="w-full py-4 ms-2 text-sm font-medium text-gray-900">{{ $u->name . " (" . $u->email . ")" }}</span>
                    </label>
                @endforeach
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

            <button type="submit" class="btn btn-primary">Criar conversa</button>

        </form>
    </div>

</x-guest-layout>