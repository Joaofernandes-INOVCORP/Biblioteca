<?php

namespace App\Http\Controllers;

use App\Mail\NewReviewAlert;
use App\Mail\ReviewResponse;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Mail;

class ReviewController extends Controller
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
    public function store(Request $request)
    {
        $data = $request->validate([
            'requisicao_id' => 'required|exists:requisicaos,id',
            'pontuacao' => 'required|numeric|integer|max:10',
            'comentario' => 'sometimes|string'
        ]);


        $review = Review::create([
            'user_id' => auth()->user()->id,
            'requisicao_id' => $data['requisicao_id'],
            'pontuacao' => $data['pontuacao'],
            'comentario' => $data['comentario'],
        ]);

        Mail::to(User::where('role', '=', 'admin')->get())
            ->send(new NewReviewAlert($review));

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
        $review = Review::findOrFail($id);

        $data = $request->validate([
            'estado' => 'required|in:ativo,recusado',
            'justificacao' => 'required_if:estado,recusado'
        ]);

        $review->estado = $data['estado'];

        Mail::to($review->user())->send(new ReviewResponse($data, $review));


        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
