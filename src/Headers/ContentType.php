<?php

namespace Hyqo\Http\Headers;

use function Hyqo\Parser\parse_pair;
use function Hyqo\String\s;

class ContentType
{
    public const JSON = 'application/json';
    public const FORM = 'application/x-www-form-urlencoded';
    public const FORM_DATA = 'multipart/form-data';
    public const TEXT = 'text/plain';
    public const HTML = 'text/html';

    protected $mediaChunks = null;
    protected $mediaType = null;
    protected $charset = null;
    protected $boundary = null;

    public function __toString(): string
    {
        if (null === $this->mediaType) {
            return '';
        }

        $string = $this->mediaType;

        if (null !== $this->charset) {
            $string .= sprintf('; charset=%s', $this->charset);
        }

        if (null !== $this->boundary && ('multipart' === $this->mediaChunks[0] ?? null)) {
            $string .= sprintf('; boundary=%s', $this->charset);
        }

        return $string ?? '';
    }

    public function set(string $value = null): self
    {
        if (null === $value) {
            return $this;
        }

        $parts = s($value)->splitStrictly(';');

        if (
            count($parts) >= 1
            && preg_match(
                '/^(?P<type>text|application|image|audio|video|font|message|model|multipart)\/(?P<subtype>[\w\-.+]+)$/i',
                $parts[0],
                $matches
            )) {
            $this->mediaChunks = [strtolower($matches['type']), strtolower($matches['subtype'])];

            $this->mediaType = implode('/', $this->mediaChunks);

            foreach (array_slice($parts, 1) as $part) {
                if (null === $pair = parse_pair($part)) {
                    continue;
                }

                [$key, $value] = $pair;

                if (null === $this->boundary && $this->mediaChunks[0] === 'multipart'
                    && (strtolower($key) === 'boundary' && preg_match('/^.{1,70}$/', $value))) {
                    $this->boundary = $value;
                }

                if (null === $this->charset && strtolower($key) === 'charset') {
                    $this->charset = $value;
                }
            }
        }

        return $this;
    }

    public function getMediaType(): ?string
    {
        return $this->mediaType;
    }

    public function getCharset(): ?string
    {
        return $this->charset;
    }

    public function getBoundary(): ?string
    {
        return $this->boundary;
    }
}
