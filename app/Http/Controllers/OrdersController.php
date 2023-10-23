<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class OrdersController extends Controller
{
    public function index(): Response
    {
        $orders = Order::orderByDesc('id')->paginate(15);
        $orders->load('client');

        return Inertia::render('Orders/Index', [
            'orders' => $orders,
            'clients' => Client::all(),
        ]);
    }

    public function store(Request $request, Order $order)
    {
        $data = $request->validate([
            'number' => ['required', 'string', 'max:255'],
            'date' => ['required', 'string', 'max:255', 'date'],
            'cost' => ['required', 'numeric', 'between:0,999999'],
            'client_id' => ['required', 'integer', Rule::exists(Client::class, 'id')],
        ]);

        $order->fill($data);

        DB::beginTransaction();
        $order->save();
        DB::commit();
    }

    public function delete(Order $order) {
        DB::beginTransaction();
        $order->delete();
        DB::commit();
    }
}