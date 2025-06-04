<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\ChatRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ChatRoom $chatRoom)
    {
        abort_if(!$chatRoom->users->contains(Auth::id()), 403);

        $data = $request->validate([
            'conteudo' => 'required|string|max:5000',
        ]);

        $mensagem = $chatRoom->messages()->create([
            'user_id' => Auth::id(),
            'conteudo' => $data['conteudo'],
        ]);

        LogController::registarLog('mensagem', 'envio', $mensagem->id);

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function destroy(Message $message)
    {
        abort_if($message->user_id !== Auth::id() && !Auth::user()->isAdmin(), 403);

        $message->delete();

        LogController::registarLog('mensagem', 'eliminação', $message->id);

        return back();
    }
}
