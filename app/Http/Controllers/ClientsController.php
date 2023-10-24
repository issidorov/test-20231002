<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ClientsController extends Controller
{
    public function index(): Response
    {
        $clients = Client::orderByDesc('id')->paginate(15);

        return Inertia::render('Clients/Index', [
            'clients' => $clients,
        ]);
    }

    public function store(Request $request, Client $client): void
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        $client->fill($data);

        DB::beginTransaction();
        $client->save();
        DB::commit();
    }

    public function delete(Client $client): void
    {
        DB::beginTransaction();
        $client->delete();
        DB::commit();
    }
}