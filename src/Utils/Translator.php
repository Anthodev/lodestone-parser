<?php

declare(strict_types=1);

namespace LodestoneUtils;

use Lodestone\Enum\LocaleEnum;
use Lodestone\Translation\DE;
use Lodestone\Translation\FR;
use Lodestone\Translation\JA;

class Translator
{
    public static function translate(
        string $locale,
        string $text,
    ): string {
        $localeClass = match ($locale) {
            LocaleEnum::FR->value => new FR(),
            LocaleEnum::DE->value => new DE(),
            LocaleEnum::JA->value => new JA(),
            default => null,
        };

        if ($localeClass === null) {
            return $text;
        }

        return $localeClass->translate($text);
    }
}
