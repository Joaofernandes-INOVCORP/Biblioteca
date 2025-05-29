<x-guest-layout>
    <div class="overflow-x-auto rounded-box border border-base-content/5 bg-orange-300">
        <table class="table">
            <!-- head -->
            <thead class="text-black font-bold text-lg ">
                <tr>
                    <th>Utilizador</th>
                    <th>Modelo</th>
                    <th>Alteração</th>
                    <th>Id do Objeto</th>
                    <th>Endereço de IP</th>
                    <th>Browser Utilizado</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($logs as $log)
                    <tr>
                        <td>{{ $log->user->name }}</td>
                        <td>{{$log->modulo }}</td>
                        <td>{{$log->alteracao }}</td>
                        <td>{{$log->objeto_id }}</td>
                        <td>{{$log->ip }}</td>
                        <td>{{$log->browser }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-guest-layout>