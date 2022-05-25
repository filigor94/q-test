<?php

declare(strict_types=1);

namespace App\Extensions;

use App\Dto\AccessTokenDto;
use App\Services\ClientService;
use App\Tools\AccessToken;
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
        $accessToken = new AccessToken(
            new AccessTokenDto(
                $payload['access_token'],
                $payload['refresh_token'],
                $payload['access_token_expires_at'],
                $payload['refresh_token_expires_at'],
                $payload['additional_data']
            )
        );

        if (!$accessToken->hasExpired()) {
            $payload['id'] = $identifier;
            $payload['remember_token'] = '';

            return new GenericUser($payload);
        }

        if ($accessToken->hasRefreshExpired()) {
            return null;
        }

        $payload = $this->clientService->getRefreshToken($accessToken)?->getValues();
        if (is_null($payload)) {
            return null;
        }

        $payload['id'] = json_encode($payload);
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

        $payload = $this->clientService
            ->getAccessToken($credentials['email'], $credentials['password'])
            ?->getValues();

        if (is_null($payload)) {
            return null;
        }

        $payload['id'] = json_encode($payload);

        return new GenericUser($payload);
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return true;
    }
}
