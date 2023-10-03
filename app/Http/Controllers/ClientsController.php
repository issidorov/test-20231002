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

        return Inertia::render('Clients/ClientsIndex', [
            'clients' => $clients,
        ]);
    }

    public function store(Request $request, string $id = null)
    {
        $client = Client::findOrNew($id);

        $data = $request->validate([
            'name' => ['required', 'string'],
            'address' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
        ]);

        $client->fill($data);

        DB::beginTransaction();
        $client->save();
        DB::commit();
    }

    public function delete(string $id) {
        Client::findOrFail($id)->delete();
    }
}