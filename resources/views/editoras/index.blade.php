<x-guest-layout>
  <div class="min-h-screen py-16">
    <div class="container mx-auto px-4">
      <h2 class="text-2xl font-bold text-gray-900 mb-8">
        Montra de Editoras
      </h2>

      <div class="my-3">
        <form action="" method="get" class="flex flex-wrap gap-3">
          <label class="floating-label w-full">
            <input type="text" placeholder="Search" class="input input-md bg-orange-100 border border-base-100 w-full" name="search" />
            <span class="text-white">Search</span>
          </label>

          <select class="select bg-orange-100 border border-base-100" name="order">
            <option selected value="asc">Order ascendente</option>
            <option value="desc">Order descendente</option>
          </select>

          <button type="submit" class="btn btn-primary">Search</button>
        </form>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($editoras as $editora)
        <div class="card bg-orange-400 shadow-lg">
          <figure class="px-4 pt-4">
            <img
              src="{{ $editora->logo
                  ? asset('storage/' . $editora->logo)
                  : 'https://via.placeholder.com/300x150?text=Sem+Logo' }}"
              alt="{{ $editora->nome }}"
              class="rounded-lg h-32 w-full object-contain" />
          </figure>
          <div class="card-body">
            <h2 class="card-title">{{ $editora->nome }}</h2>
            <p class="text-sm text-gray-600">
              Livros: {{ $editora->livros->count() }}
            </p>
            <div class="card-actions justify-end">
              <a
                href="{{ route('editoras.show', $editora) }}"
                class="btn btn-sm btn-primary">
                Ver
              </a>
            </div>
          </div>
        </div>
        @endforeach
      </div>

      <div class="mt-8 flex justify-center">
        {{ $editoras->links() }}
      </div>
    </div>
  </div>
</x-guest-layout>