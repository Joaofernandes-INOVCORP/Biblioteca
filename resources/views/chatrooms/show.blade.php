<x-guest-layout>

    <div class="h-[calc(100dvh-7rem)] flex flex-row">
        <div class="grow-[3] border border-x-orange-950 flex flex-col gap-4">
            <div class="grow overflow-y-auto px-4">
                @foreach ($chatRoom->messages as $msg)
                    @if ($msg->sender->id == auth()->user()->id)
                        <div
                            class="max-w-xs md:max-w-2xl my-10 class flex flex-col gap-2 place-self-end rounded-md bg-orange-200 px-3 py-2">
                            <span class="text-sm opacity-50">{{ $msg->sender->name }}</span>
                            <span class="px-10">{{ $msg->conteudo }}</span>
                        </div>
                    @else
                        <div
                            class="max-w-xs md:max-w-2xl min-w-32 my-10 class flex flex-col gap-2 place-self-start rounded-md bg-orange-200 px-3 py-2">
                            <span class="text-sm opacity-50">{{ $msg->sender->name }}</span>
                            <span class="px-10">{{ $msg->conteudo }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="h-30 px-4 bg-orange-200 py-4">
                <form onclick="" class="flex items-baseline gap-6">
                    <textarea
                        id="msg"
                        class="textarea textarea-ghost textarea-lg grow focus:bg-transparent focus:border-gray-800"
                        placeholder="Escreva aqui..." rows="1"></textarea>
                    <button type="submit" class="bg-sky-500 rounded-full p-2 flex justify-center items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6 fill-white" viewBox="0 -1 18 17">
                            <path
                                d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        <div class="w-1/4 border border-x-orange-950 flex flex-col gap-3 ps-4 pt-4">
            @foreach (auth()->user()->chatRooms as $cr)
                <a href="{{ route("chatrooms.show", $cr) }}"
                    class="px-4 py-2 bg-orange-{{ $cr->nome == $chatRoom->nome ? '400' : '300' }} rounded-s-lg">{{ $cr->nome }}</a>
            @endforeach
        </div>
    </div>

</x-guest-layout>