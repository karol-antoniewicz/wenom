<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;

/**
 * Passport client controller
 */
class PassportController extends Controller
{
    /**
     * List all OAuth clients.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Querying the Client model to get a list of clients with specific fields.
        $clients = Client::query()
            ->select('id', 'name', 'secret', 'created_at')
            ->get()
            ->each(fn (Client $client): Client => $client->makeVisible('secret'));

        return response()->json($clients);
    }

    /**
     * Create a new OAuth client, delete existing clients.
     *
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
        Client::truncate();

        // Querying the Client model to get a list of clients with specific fields.
        $client = (new ClientRepository)->create(null, 'SVWS-Server', '');

        return response()->json([
            'id' => $client->id,
            'name' => $client->name,
            'secret' => $client->secret,
            'created_at' => $client->created_at,
        ], Response::HTTP_CREATED);
    }
}
