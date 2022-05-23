<?php

declare(strict_types=1);

namespace App\Extensions;

use App\Services\ClientService;
use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class ClientUserProvider implements UserProvider
{
    public function __construct(private ClientService $clientService)
    {
    }

    public function retrieveById($identifier)
    {
        $payload = json_decode($identifier, true);
        $payload['id'] = $identifier;
        $payload['remember_token'] = '';

        return new GenericUser($payload);
    }

    public function retrieveByToken($identifier, $token)
    {
        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
    }

    public function retrieveByCredentials(array $credentials)
    {
        if (!array_key_exists('email', $credentials)) {
            return null;
        }

        if (!array_key_exists('password', $credentials)) {
            return false;
        }

        $loginResultDto = $this->clientService->login($credentials['email'], $credentials['password']);

        if (!$loginResultDto->getSuccess() || is_null($loginResultDto->getAccessToken())) {
            return null;
        }

        $payload = $loginResultDto->getAccessToken()->getValues();
        $payload['id'] = json_encode($payload);

        return new GenericUser($payload);
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return true;
    }
}
