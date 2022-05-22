<?php

namespace Hyqo\Http\Header;

use function Hyqo\String\s;

class AcceptLanguage
{
    protected $languages = [];

    public function set(string $value = null): self
    {
        if (null === $value) {
            return $this;
        }

        $parts = s($value)->splitStrictly(',');

        $languages = [];
        foreach ($parts as $part) {
            if (preg_match(
                '/^(?:(?P<language>[a-z]{2})(?:-(?P<variety>[a-z]+))?|(?P<all>\*))(?:;q=(?P<quality>0.\d+))?$/i',
                $part,
                $matches
            )) {
                $language = ($matches['all'] ?? '') ?: strtolower($matches['language']);
//                $variety = strtolower($matches['variety'] ?? '');
                $quality = $matches['quality'] ?? 1;

                if (!isset($this->languages[$language])) {
                    $this->languages[$language] = [];
                }

                $languages[] = [$language, $quality];
            }
        }

        usort($languages, static function (array $langA, array $langB) {
            if ($langA[1] === $langB[1]) {
                return 0;
            }
            return ($langA[1] < $langB[1]) ? 1 : -1;
        });

        $languages = array_map(static function (array $lang) {
            return $lang[0];
        }, $languages);

        $languages = array_values(array_unique($languages));

        $this->languages = $languages;

        return $this;
    }

    public function getAll(): array
    {
        return $this->languages;
    }
}
