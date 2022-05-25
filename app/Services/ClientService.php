<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\AccessTokenDto;
use App\Dto\LoginResultDto;
use App\Tools\AccessToken;
use Illuminate\Http\Client\Response;
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

    public function fetchAuthors(int $page = 1, int $perPage = 12, ?string $search = null): Response
    {
        $response = Http::qClientWithToken()->get('/api/v2/authors', [
            'limit' => $perPage,
            'page' => $page,
            'query' => $search,
        ]);

        return $this->getValidatedResponse($response);
    }

    public function fetchAuthor(int $id): Response
    {
        $response = Http::qClientWithToken()->get('/api/v2/authors/'.$id);

        return $this->getValidatedResponse($response);
    }

    public function deleteAuthor(int $id): Response
    {
        $response = Http::qClientWithToken()->delete('/api/v2/authors/'.$id);

        return $this->getValidatedResponse($response);
    }

    public function deleteBook(int $id): Response
    {
        $response = Http::qClientWithToken()->delete('/api/v2/books/'.$id);

        return $this->getValidatedResponse($response);
    }

    public function createAuthor(
        string $firstName,
        string $lastName,
        string $birthDay,
        string $placeOfBirth,
        string $biography = null,
        string $gender = null
    ): Response {
        $response = Http::qClientWithToken()->post('/api/v2/authors', [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'birthday' => $birthDay,
            'biography' => $biography,
            'gender' => $gender,
            'place_of_birth' => $placeOfBirth,
        ]);

        return $this->getValidatedResponse($response);
    }

    public function createBook(
        string $authorId,
        string $title,
        string $isbn,
        ?string $releaseDate = null,
        ?string $description = null,
        ?string $format = null,
        ?string $numberOfPages = null,
    ): Response {
        $response = Http::qClientWithToken()->post('/api/v2/books', [
            'author' => [
                'id' => $authorId,
            ],
            'title' => $title,
            'release_date' => $releaseDate,
            'description' => $description,
            'isbn' => $isbn,
            'format' => $format,
            'number_of_pages' => $numberOfPages,
        ]);

        return $this->getValidatedResponse($response);
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

        return $this->createAccessToken(
            $this->getValidatedResponse($response)->json()
        );
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

        return $this->createAccessToken(
            $this->getValidatedResponse($response)->json()
        );
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

    private function getValidatedResponse(Response $response): Response
    {
        $response->onError(function (Response $response) {
            $response->throw();
        });

        return $response;
    }
}
