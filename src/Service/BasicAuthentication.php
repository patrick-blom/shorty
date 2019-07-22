<?php

declare(strict_types=1);

namespace App\Service;

final class BasicAuthentication
{
    /**
     * @var string
     */
    private $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    public function validateTokenAuthentication(string $token): bool
    {
        return $this->token === $token;
    }
}
