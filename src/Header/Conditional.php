<?php

namespace Hyqo\Http\Header;

use DateTimeImmutable;
use DateTimeInterface;
use Hyqo\Http\Header;

class Conditional
{
    /** @var ETag[]|null */
    protected ?array $match;

    /** @var ETag[]|null */
    protected ?array $noneMatch;

    protected ?DateTimeImmutable $modifiedSince;

    protected ?DateTimeImmutable $unmodifiedSince;

    /** @var DateTimeImmutable|ETag|null */
    protected null|ETag|DateTimeImmutable $range;

    public function set(string $name, ?string $value): static
    {
        if (null === $value) {
            return $this;
        }

        switch ($name) {
            case Header::IF_MATCH:
                $this->setMatch($value);
                break;
            case Header::IF_NONE_MATCH:
                $this->setNoneMatch($value);
                break;
            case Header::IF_MODIFIED_SINCE:
                $this->setModifiedSince($value);
                break;
            case Header::IF_UNMODIFIED_SINCE:
                $this->setUnmodifiedSince($value);
                break;
            case Header::IF_RANGE:
                $this->setRange($value);
                break;
        }

        return $this;
    }

    public function setMatch(string $value): static
    {
        $this->match = $this->parseETags($value);

        return $this;
    }

    /** @return ETag[]|null */
    public function getMatch(): ?array
    {
        return $this->match;
    }

    public function setNoneMatch(string $value): static
    {
        $this->noneMatch = $this->parseETags($value);

        return $this;
    }

    /** @return ETag[]|null */
    public function getNoneMatch(): ?array
    {
        return $this->noneMatch;
    }

    public function setModifiedSince(string $value): static
    {
        $this->modifiedSince = $this->parseDateTime($value);

        return $this;
    }

    /** @return DateTimeImmutable|null */
    public function getModifiedSince(): ?DateTimeImmutable
    {
        return $this->modifiedSince;
    }

    public function setUnmodifiedSince(string $value): static
    {
        $this->unmodifiedSince = $this->parseDateTime($value);

        return $this;
    }

    /** @return DateTimeImmutable|null */
    public function getUnmodifiedSince(): ?DateTimeImmutable
    {
        return $this->unmodifiedSince;
    }

    public function setRange(string $value): static
    {
        $this->range = $this->parseDateTime($value) ?? (($eTags = $this->parseETags($value)) ? current($eTags) : null);

        return $this;
    }

    public function getRange(): DateTimeImmutable|ETag|null
    {
        return $this->range;
    }

    protected function parseDateTime(string $value): ?DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(DateTimeInterface::RFC7231, $value) ?: null;
    }

    /** @return ETag[]|null */
    protected function parseETags(string $value): ?array
    {
        $tags = preg_split('/(?<=")\s*,\s*(?=(W\\\\)?")/', $value);
        $result = [];

        foreach ($tags as $tag) {
            $tag = trim($tag);

            if (preg_match(
                '/^(?P<weak>W\\\\)?"(?P<value>(?:[\x20\x21\x23-\x5b\x5d-\x7e]|\r\n[\t ]|\\\\"|\\\\[^"])*)"$/',
                $tag,
                $matches
            )) {
                $result[$matches['value']] = new ETag($matches['value'], (bool)$matches['weak']);
            }
        }

        return $result ?: null;
    }
}
