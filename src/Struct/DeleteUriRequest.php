<?php

declare(strict_types=1);

namespace App\Struct;

final class DeleteUriRequest implements UriHashInterface
{
    /**
     * @var string
     */
    private $hash;

    /**
     * DeleteUriRequest constructor.
     *
     * @param string $hash
     */
    public function __construct(string $hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }
}
