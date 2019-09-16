<?php

declare(strict_types=1);

namespace App\Service\Authentication;

final class BasicDeleteAuthentication implements TokenAuthenticationInterface
{
    /**
     * @var string
     */
    private $deleteToken;

    public function __construct(string $token)
    {
        $this->deleteToken = $token;
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    public function validateTokenAuthentication(string $token): bool
    {
        return $this->deleteToken === $token;
    }
}
