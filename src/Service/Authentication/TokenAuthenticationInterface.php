<?php

declare(strict_types=1);

namespace App\Service\Authentication;

interface TokenAuthenticationInterface
{
    /**
     * @param string $token
     *
     * @return bool
     */
    public function validateTokenAuthentication(string $token): bool;
}
