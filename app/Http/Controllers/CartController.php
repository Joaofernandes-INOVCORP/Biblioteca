<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItems;
use App\Models\Encomenda;
use App\Models\Livro;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cart = auth()->user()->cart;
        if (auth()->user()->isAdmin()) {
            $enc = Encomenda::with(['items.livro', 'user'])->get();
        } else {
            $enc = null;
        }

        return view('carts.index', compact('cart', 'enc'));
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
            "livro_id" => "required|exists:livros,id",
            "amount" => "required|numeric|integer"
        ]);


        $livro = Livro::find($data["livro_id"]);


        if (($livro->stock - ($livro->requisicoes()->where('status', '=', 'ativa')->count() + (auth()->user()->cart?->items->where('livro_id', '=', $data["livro_id"])->sum('amount') ?? 0))) < $data["amount"]) {
            return back()->withErrors('Livros insuficientes!');
        }

        $cart = auth()->user()->cart;

        if (!$cart) {
            $cart = Cart::create(["user_id" => auth()->user()->id]);
        }

        $item = $cart->items()->where("livro_id", '=', $data["livro_id"])->first();

        if (!$item) {
            CartItems::create([
                "cart_id" => $cart->id,
                "livro_id" => $data["livro_id"],
                'amount' => $data["amount"]
            ]);
        } else {
            $item->amount += $data["amount"];
            $item->save();
        }

        return redirect(route("cart.index"));
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            "livro_id" => "required|exists:livros,id",
            "amount" => "required|numeric|integer"
        ]);


        $livro = Livro::find($data["livro_id"]);


        if ($livro->stock - ($livro->requisicoes()->where('status', '=', 'ativa')->count()) < $data["amount"]) {
            return back()->withErrors('Livros insuficientes!');
        }

        $cart = auth()->user()->cart;

        if (!$cart) {
            $cart = Cart::create(["user_id" => auth()->user()->id]);
        }

        $item = $cart->items()->where("livro_id", '=', $data["livro_id"])->first();

        if (!$item) {
            CartItems::create([
                "cart_id" => $cart->id,
                "livro_id" => $data["livro_id"],
                'amount' => $data["amount"]
            ]);
        } else {
            if ($data["amount"] == 0) {
                CartItems::destroy($item->id);

                if ($cart->items()->count() == 0)
                    Cart::destroy($cart->id);

                return redirect(route("home"));
            }

            $item->amount = $data["amount"];
            $item->save();
        }

        return redirect(route("cart.index"));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $data = $request->validate([
            "livro_id" => "sometimes|exists:livro"
        ]);


        $cart = auth()->user()->cart();

        if (!$cart)
            return back();

        if ($data["livro_id"]) {
            $item = CartItems::find($data["livro_id"]);

            $item->destroy();

            return redirect()->route('cart.index');
        }

        $item = CartItems::where("cart_id", '=', $cart->id)->get();

        $item->destroy();
        $cart->destroy($cart->id);

        return redirect()->route("home");
    }
}
