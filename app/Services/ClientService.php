<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\AccessTokenDto;
use App\Dto\LoginResultDto;
use App\Tools\AccessToken;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class ClientService
{
    public function login(string $email, string $password): LoginResultDto
    {
        try {
            $accessToken = $this->getAccessToken($email, $password);
        } catch (\Exception $exception) {
            return new LoginResultDto(false);
        }

        return new LoginResultDto(true, $accessToken);
    }

    public function fetchAuthors(int $page = 1, int $perPage = 12): Collection
    {
        $response = Http::qClientWithToken()->get('/api/v2/authors', [
            'limit' => $perPage,
            'page' => $page,
        ]);

        $response->onError(function (Response $response) {
            $response->throw();
        });

        return $response->collect();
    }

    public function fetchAuthor(int $id): array
    {
        $response = Http::qClientWithToken()->get('/api/v2/authors/'.$id);

        $response->onError(function (Response $response) {
            $response->throw();
        });

        return $response->json();
    }

    public function deleteAuthor(int $id): bool
    {
        $response = Http::qClientWithToken()->delete('/api/v2/authors/'.$id);

        $response->onError(function (Response $response) {
            $response->throw();
        });

        return $response->ok();
    }

    public function deleteBook(int $id): bool
    {
        $response = Http::qClientWithToken()->delete('/api/v2/books/'.$id);

        $response->onError(function (Response $response) {
            $response->throw();
        });

        return $response->ok();
    }

    public function createAuthor(
        string $firstName,
        string $lastName,
        string $birthDay,
        string $placeOfBirth,
        string $biography = null,
        string $gender = null
    ): array {
        $response = Http::qClientWithToken()->post('/api/v2/authors', [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'birthday' => $birthDay,
            'biography' => $biography,
            'gender' => $gender,
            'place_of_birth' => $placeOfBirth,
        ]);

        $response->onError(function (Response $response) {
            $response->throw();
        });

        return $response->json();
    }

    protected function fetchAccessToken(?string $email, ?string $password): AccessToken
    {
        if (is_null($email) || is_null($password)) {
            throw new \Exception('Email and password are required');
        }

        $response = Http::qClient()->post(config('client.url_access_token'), [
            'email' => $email,
            'password' => $password,
        ]);

        $response->onError(function (Response $response) {
            $response->throw();
        });

        return $this->createAccessToken($response->json());
    }

    protected function getAccessToken(string $email = null, string $password = null): AccessToken
    {
        if (!auth()->check()) {
            return $this->fetchAccessToken($email, $password);
        }

        $identifier = json_decode(auth()->user()->id, true);
        $accessToken = new AccessToken(
            new AccessTokenDto(
                $identifier['access_token'],
                $identifier['refresh_token'],
                $identifier['access_token_expires_at'],
                $identifier['refresh_token_expires_at'],
                $identifier['additional_data']
            )
        );

        if (!$accessToken->hasExpired()) {
            return $accessToken;
        }

        if (!$accessToken->hasRefreshExpired()) {
            return $this->fetchRefreshToken($accessToken);
        }

        return $this->fetchAccessToken($email, $password);
    }

    protected function fetchRefreshToken(AccessToken $accessToken): AccessToken
    {
        $response = Http::qClient()->get(config('client.url_refresh_token').'/'.$accessToken->getRefreshToken());

        $response->onError(function (Response $response) {
            $response->throw();
        });

        return $this->createAccessToken($response->json());
    }

    private function createAccessToken(array $response): AccessToken
    {
        return new AccessToken(
            new AccessTokenDto(
                $response['token_key'],
                $response['refresh_token_key'],
                $response['expires_at'],
                $response['refresh_expires_at'],
                [
                    'id' => $response['id'],
                    'user' => $response['user'],
                    'created_at' => $response['created_at'],
                    'updated_at' => $response['updated_at'],
                ]
            )
        );
    }
}
