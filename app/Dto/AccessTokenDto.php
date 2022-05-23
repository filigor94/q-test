<?php

declare(strict_types=1);

namespace App\Dto;

class AccessTokenDto
{
    public function __construct(
        private string $accessToken,
        private string $refreshToken,
        private string $expiresAt,
        private string $refreshExpiresAt,
        private array $additionalData = []
    ) {
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * @return string
     */
    public function getExpiresAt(): string
    {
        return $this->expiresAt;
    }

    /**
     * @return string
     */
    public function getRefreshExpiresAt(): string
    {
        return $this->refreshExpiresAt;
    }

    /**
     * @return array
     */
    public function getAdditionalData(): array
    {
        return $this->additionalData;
    }
}
