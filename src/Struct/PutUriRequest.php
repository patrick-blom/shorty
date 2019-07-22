<?php

declare(strict_types=1);

namespace App\Struct;

final class PutUriRequest
{
    /**
     * @var string
     */
    private $url;
    /**
     * @var string
     */
    private $urlHash;
    /**
     * @var string
     */
    private $shortCode;

    /**
     * PutUriRequest constructor.
     *
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url       = $url;
        $this->urlHash   = sha1($url);
        $this->shortCode = substr(sha1($url), 0, 8);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getUrlHash(): string
    {
        return $this->urlHash;
    }

    /**
     * @return string
     */
    public function getShortCode(): string
    {
        return $this->shortCode;
    }
}
