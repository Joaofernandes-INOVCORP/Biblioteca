<x-guest-layout>
    <a href="{{ route('chatrooms.create') }}" class="btn btn-primary">
        Criar nova conversa
    </a>

    @foreach ($salas as $s)
        <a href="{{ route('chatrooms.show', $s) }}" class="btn btn-primary">
            Abrir conversa {{ $s->nome }}
        </a>
    @endforeach
</x-guest-layout>