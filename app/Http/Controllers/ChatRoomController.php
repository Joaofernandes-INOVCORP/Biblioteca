<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatRoomController extends Controller
{
    public function index()
    {
        $salas = Auth::user()->chatRooms()->withCount('messages')->get();

        return view('chatrooms.index', compact('salas'));
    }

    public function create()
    {
        abort_if(!Auth::user()->isAdmin(), 403);

        $utilizadores = User::where('id', '!=', Auth::id())->get();

        return view('chatrooms.create', compact('utilizadores'));
    }

    public function store(Request $request)
    {
        abort_if(!Auth::user()->isAdmin(), 403);

        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'avatar' => 'nullable|image|max:2048',
            'utilizadores' => 'required|array|min:1',
            'utilizadores.*' => 'exists:users,id',
        ]);

        $avatar = $request->file('avatar')?->store('chat_avatars', 'public');

        $sala = ChatRoom::create([
            'nome' => $data['nome'],
            'avatar' => $avatar,
        ]);

        $sala->users()->sync(array_merge($data['utilizadores'], [Auth::id()]));

        LogController::registarLog('chatroom', 'criação', $sala->id);

        return redirect()->route('chatrooms.index')->with('sucesso', 'Sala criada com sucesso.');
    }

    public function show(ChatRoom $chatRoom)
    {
        abort_if(!$chatRoom->users->contains(Auth::id()), 403);

        $chatRoom->load('messages.user');

        return view('chatrooms.show', compact('chatRoom'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChatRoom $chatRoom)
    {
        abort_if(!Auth::user()->isAdmin(), 403);

        $chatRoom->delete();

        LogController::registarLog('chatroom', 'eliminação', $chatRoom->id);

        return redirect()->route('chatrooms.index')->with('sucesso', 'Sala eliminada.');
    }
}
