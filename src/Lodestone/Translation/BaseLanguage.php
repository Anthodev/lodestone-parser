<?php

declare(strict_types=1);

namespace Lodestone\Translation;

abstract class BaseLanguage
{
    private const array STRINGS = [];

    public function translate(string $text): string
    {
        if (array_key_exists($text, self::STRINGS)) {
            return self::STRINGS[$text];
        }

        return $text;
    }
}
