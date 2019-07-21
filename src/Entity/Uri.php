<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UriRepository")
 * @ORM\Table( name="uri", indexes={
 *  @ORM\Index(name="create_idx", columns={"url_hash"}),
 *  @ORM\Index(name="read_idx", columns={"short_code"})
 * })
 */
class Uri
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $OriginalUrl;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $shortCode;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $UrlHash;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginalUrl(): ?string
    {
        return $this->OriginalUrl;
    }

    public function setOriginalUrl(string $OriginalUrl): self
    {
        $this->OriginalUrl = $OriginalUrl;

        return $this;
    }

    public function getShortCode(): ?string
    {
        return $this->shortCode;
    }

    public function setShortCode(string $shortCode): self
    {
        $this->shortCode = $shortCode;

        return $this;
    }

    public function getUrlHash(): ?string
    {
        return $this->UrlHash;
    }

    public function setUrlHash(string $UrlHash): self
    {
        $this->UrlHash = $UrlHash;

        return $this;
    }
}
