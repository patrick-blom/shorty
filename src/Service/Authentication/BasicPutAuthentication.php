<?php

declare(strict_types=1);

namespace App\Service\Authentication;

final class BasicPutAuthentication implements TokenAuthenticationInterface
{
    /**
     * @var string
     */
    private $putToken;

    public function __construct(string $token)
    {
        $this->putToken = $token;
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    public function validateTokenAuthentication(string $token): bool
    {
        return $this->putToken === $token;
    }
}
