<?php

declare(strict_types=1);

namespace App\Struct;

interface UriHashInterface
{
    /**
     * @return string
     */
    public function getHash(): string;
}
