<?php

namespace Dropwire\Passport;

use Laravel\Passport\ClientRepository as BaseRepository;
use Laravel\Passport\Passport;
use Laravel\Passport\PersonalAccessClient;

class ClientRepository extends BaseRepository
{
    /**
     * Get a client by the given ID.
     *
     * @param  int  $id
     * @return Client|null
     */
    public function find($id)
    {
        return Client::find($id);
    }

    /**
     * Get the client instances for the given user ID.
     *
     * @param  mixed  $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function forUser($userId)
    {
        return Client::where('user_id', $userId)
                        ->orderBy('name', 'desc')->get();
    }

    /**
     * Get the personal access token client for the application.
     *
     * @return Client
     */
    public function personalAccessClient()
    {
        if (Passport::$personalAccessClient) {
            return Client::find(Passport::$personalAccessClient);
        } else {
            return PersonalAccessClient::orderBy('id', 'desc')->first()->client;
        }
    }

    /**
     * Store a new client.
     *
     * @param  int  $userId
     * @param  string  $name
     * @param  string  $redirect
     * @param  bool  $personalAccess
     * @param  bool  $password
     * @return Client
     */
    public function create($userId, $name, $redirect, $personalAccess = false, $password = false)
    {
        $client = (new Client)->forceFill([
            'user_id' => $userId,
            'name' => $name,
            'secret' => str_random(40),
            'redirect' => $redirect,
            'personal_access_client' => $personalAccess,
            'password_client' => $password,
            'revoked' => false,
        ]);

        $client->save();

        return $client;
    }

    /**
     * Update the given client.
     *
     * @param  Client  $client
     * @param  string  $name
     * @param  string  $redirect
     * @return Client
     */
    public function update(Client $client, $name, $redirect)
    {
        $client->forceFill([
            'name' => $name, 'redirect' => $redirect,
        ])->save();

        return $client;
    }

    /**
     * Regenerate the client secret.
     *
     * @param  Client  $client
     * @return Client
     */
    public function regenerateSecret(Client $client)
    {
        $client->forceFill([
            'secret' => str_random(40),
        ])->save();

        return $client;
    }

    /**
     * Determine if the given client is revoked.
     *
     * @param  int  $id
     * @return bool
     */
    public function revoked($id)
    {
        return Client::where('id', $id)
                ->where('revoked', true)->exists();
    }

    /**
     * Delete the given client.
     *
     * @param  Client  $client
     * @return void
     */
    public function delete(Client $client)
    {
        $client->tokens()->update(['revoked' => true]);

        $client->forceFill(['revoked' => true])->save();
    }
}
