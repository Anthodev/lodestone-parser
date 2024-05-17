<?php

declare(strict_types=1);

namespace Lodestone\Enum;

enum LocaleEnum: string
{
    case EN = 'en';
    case FR = 'fr';
    case DE = 'de';
    case JA = 'ja';

    public static function getEnumValues(): array
    {
        return [self::EN->value, self::FR->value, self::DE->value, self::JA->value];
    }

    public static function isValid(string $locale): bool
    {
        return in_array($locale, self::getEnumValues(), true);
    }
}
