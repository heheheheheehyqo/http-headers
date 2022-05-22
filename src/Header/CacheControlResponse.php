<?php

namespace Hyqo\Http\Header;

use Hyqo\Http\Header;

class CacheControlResponse extends CacheControl
{
    public const S_MAXAGE = 's-maxage';

    public const MUST_REVALIDATE = 'must-revalidate';
    public const PROXY_REVALIDATE = 'proxy-revalidate';
    public const MUST_UNDERSTAND = 'must-understand';

    public const PRIVATE = 'private';
    public const PUBLIC = 'public';

    public const IMMUTABLE = 'immutable';
    public const STALE_WHILE_REVALIDATE = 'stale-while-revalidate';
    public const STALE_IF_ERROR = 'stale-if-error';

    protected const WITH_VALUE = [
        self::MAX_AGE,
        self::S_MAXAGE,
        self::STALE_WHILE_REVALIDATE,
        self::STALE_IF_ERROR,
    ];

    public function header(): ?string
    {
        if ($this->has(self::NO_STORE)) {
            return Header::CACHE_CONTROL . ': ' . self::NO_STORE;
        }

        if ($this->directives) {
            return Header::CACHE_CONTROL . ': ' . $this;
        }

        return null;
    }

    public function setNoCache(): self
    {
        $this->setDirective(self::NO_CACHE);

        return $this;
    }

    public function setNoStore(): self
    {
        $this->setDirective(self::NO_STORE);

        return $this;
    }

    public function setNoTransform(): self
    {
        $this->setDirective(self::NO_TRANSFORM);

        return $this;
    }

    public function setPublic(): self
    {
        $this->removeDirective(self::PRIVATE);
        $this->setDirective(self::PUBLIC);

        return $this;
    }

    public function setPrivate(): self
    {
        $this->removeDirective(self::PUBLIC);
        $this->setDirective(self::PRIVATE);

        return $this;
    }

    public function setMaxAge(int $value): self
    {
        $this->setDirective(self::MAX_AGE, $value);

        return $this;
    }

    public function setSMaxAge(int $value): self
    {
        $this->setDirective(self::S_MAXAGE, $value);

        return $this;
    }

    public function setMustRevalidate(): self
    {
        $this->setDirective(self::MUST_REVALIDATE);

        return $this;
    }

    public function setProxyRevalidate(): self
    {
        $this->setDirective(self::PROXY_REVALIDATE);

        return $this;
    }

    public function setMustUnderstand(): self
    {
        $this->setDirective(self::MUST_UNDERSTAND);

        return $this;
    }

    public function setImmutable(): self
    {
        $this->setDirective(self::IMMUTABLE);

        return $this;
    }

    public function setStaleWhileRevalidate(int $value): self
    {
        $this->setDirective(self::STALE_WHILE_REVALIDATE, $value);

        return $this;
    }

    public function setStaleIfError(int $value): self
    {
        $this->setDirective(self::STALE_IF_ERROR, $value);

        return $this;
    }

}
