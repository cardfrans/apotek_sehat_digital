<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::with('medicine')
            ->where('user_id', auth()->id())
            ->get();

        return view('orders.cart', compact('cartItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id'
        ]);

        $medicine = Medicine::findOrFail($request->medicine_id);

        if ($medicine->stock < 1) {
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Maaf, stok obat ini sedang habis!'], 400);
            }
            return redirect()->back()->with('error', 'Maaf, stok obat ini sedang habis!');
        }

        $existingCart = CartItem::where('user_id', auth()->id())
            ->where('medicine_id', $request->medicine_id)
            ->first();

        if ($existingCart) {
            if ($medicine->stock < ($existingCart->quantity + 1)) {
                if ($request->ajax()) {
                    return response()->json(['status' => 'error', 'message' => 'Jumlah keranjang melebihi batas stok gudang!'], 400);
                }
                return redirect()->back()->with('error', 'Jumlah keranjang melebihi batas stok gudang!');
            }
            $existingCart->increment('quantity');
        } else {
            CartItem::create([
                'user_id' => auth()->id(),
                'medicine_id' => $request->medicine_id,
                'quantity' => 1
            ]);
        }

        $cartCount = CartItem::where('user_id', auth()->id())->sum('quantity');

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Obat berhasil dimasukkan ke keranjang!',
                'cart_count' => $cartCount
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Obat berhasil dimasukkan ke keranjang!');
    }

    public function update(Request $request, $id)
    {
        $cartItem = CartItem::findOrFail($id);
        $medicine = Medicine::findOrFail($cartItem->medicine_id);

        if ($request->action === 'increase') {
            if ($medicine->stock < ($cartItem->quantity + 1)) {
                return redirect()->back()->with('error', 'Stok obat tidak mencukupi.');
            }
            $cartItem->increment('quantity');
        } elseif ($request->action === 'decrease') {
            if ($cartItem->quantity > 1) {
                $cartItem->decrement('quantity');
            } else {
                $cartItem->delete();
            }
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        $cartItem = CartItem::where('user_id', auth()->id())->findOrFail($id);
        $cartItem->delete();

        return redirect()->back()->with('success', 'Obat berhasil dikeluarkan dari keranjang.');
    }

    // FIX ALUR UTAMA: Cukup lempar parameter penanda string 'cart' tanpa melahirkan data order kosong di database
    public function checkout()
    {
        $cartItems = CartItem::where('user_id', auth()->id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('dashboard')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        return redirect()->route('orders.checkout', 'cart');
    }
}