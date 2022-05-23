<?php

declare(strict_types=1);

namespace App\Dto;

use App\Tools\AccessToken;

class LoginResultDto
{
    public function __construct(
        private bool $success,
        private ?AccessToken $accessToken = null
    ) {
    }

    public function getSuccess(): bool
    {
        return $this->success;
    }

    public function getAccessToken(): ?AccessToken
    {
        return $this->accessToken;
    }
}
