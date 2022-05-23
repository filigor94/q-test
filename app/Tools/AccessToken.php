<?php

declare(strict_types=1);

namespace App\Tools;

use App\Dto\AccessTokenDto;
use Carbon\Carbon;

class AccessToken
{
    protected string $accessToken;
    protected string $refreshToken;
    protected string $accessTokenExpiresAt;
    protected string $refreshTokenExpiresAt;
    protected array $additionalData;
    protected array $values;

    public function __construct(AccessTokenDto $accessTokenDto)
    {
        $this->accessToken = $accessTokenDto->getAccessToken();
        $this->refreshToken = $accessTokenDto->getRefreshToken();
        $this->accessTokenExpiresAt = $accessTokenDto->getExpiresAt();
        $this->refreshTokenExpiresAt = $accessTokenDto->getRefreshExpiresAt();
        $this->additionalData = $accessTokenDto->getAdditionalData();

        $this->values = [
            'access_token' => $this->accessToken,
            'refresh_token' => $this->refreshToken,
            'access_token_expires_at' => $this->accessTokenExpiresAt,
            'refresh_token_expires_at' => $this->refreshTokenExpiresAt,
            'additional_data' => $this->additionalData,
        ];
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
     * @return array
     */
    public function getAdditionalData(): array
    {
        return $this->additionalData;
    }

    public function hasExpired(): bool
    {
        return Carbon::parse($this->accessTokenExpiresAt)->isBefore(now());
    }

    public function hasRefreshExpired(): bool
    {
        return Carbon::parse($this->refreshTokenExpiresAt)->isBefore(now());
    }

    public function getValues(): array
    {
        return $this->values;
    }
}
