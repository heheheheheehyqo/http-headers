<?php

namespace Hyqo\Http\Headers;

class ContentLanguage extends AcceptLanguage
{
    public function __toString(): string
    {
        return implode(
            ', ',
            array_map(static function (string $language, array $varieties): string {
                if ($varieties === [$language]) {
                    return $language;
                }

                return implode(
                    ', ',
                    array_map(static function (string $variety) use ($language): string {
                        return sprintf('%s-%s', $language, $variety);
                    }, $varieties)
                );
            }, array_keys($this->languages), array_values($this->languages))
        );
    }
}
