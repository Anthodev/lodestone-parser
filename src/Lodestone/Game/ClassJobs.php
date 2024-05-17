<?php

namespace Lodestone\Game;

use Lodestone\Enum\LocaleEnum;

class ClassJobs
{
    // pull from: https://xivapi.com/classjob?columns=ID,Name
    /** @var array<int, string> */
    const array ROLES = [
        0 => 'adventurer',
        1 => 'gladiator',
        2 => 'pugilist',
        3 => 'marauder',
        4 => 'lancer',
        5 => 'archer',
        6 => 'conjurer',
        7 => 'thaumaturge',
        8 => 'carpenter',
        9 => 'blacksmith',
        10 => 'armorer',
        11 => 'goldsmith',
        12 => 'leatherworker',
        13 => 'weaver',
        14 => 'alchemist',
        15 => 'culinarian',
        16 => 'miner',
        17 => 'botanist',
        18 => 'fisher',
        19 => 'paladin',
        20 => 'monk',
        21 => 'warrior',
        22 => 'dragoon',
        23 => 'bard',
        24 => 'white mage',
        25 => 'black mage',
        26 => 'arcanist',
        27 => 'summoner',
        28 => 'scholar',
        29 => 'rogue',
        30 => 'ninja',
        31 => 'machinist',
        32 => 'dark knight',
        33 => 'astrologian',
        34 => 'samurai',
        35 => 'red mage',
        36 => 'blue mage',
        37 => 'gunbreaker',
        38 => 'dancer',
        39 => 'reaper',
        40 => 'sage',
    ];

    /**
     * This provides a link between a class/job, select
     * any id from either a class or job and it will return the class/job ids
     *
     *  ROLE => [ CLASS_ID, JOB_ID ]
     */
    const CLASS_JOB_LINKS = [
        0 =>  [ 0, 0 ],
        1 =>  [ 1, 19 ],
        2 =>  [ 2, 20 ],
        3 =>  [ 3, 21 ],
        4 =>  [ 4, 22 ],
        5 =>  [ 5, 23 ],
        6 =>  [ 6, 24 ],
        7 =>  [ 7, 25 ],
        8 =>  [ 8, 8 ],
        9 =>  [ 9, 9 ],
        10 => [ 10, 10 ],
        11 => [ 11, 11 ],
        12 => [ 12, 12 ],
        13 => [ 13, 13 ],
        14 => [ 14, 14 ],
        15 => [ 15, 15 ],
        16 => [ 16, 16 ],
        17 => [ 17, 17 ],
        18 => [ 18, 18 ],
        19 => [ 1, 19 ],
        20 => [ 2, 20 ],
        21 => [ 3, 21 ],
        22 => [ 4, 22 ],
        23 => [ 5, 23 ],
        24 => [ 6, 24 ],
        25 => [ 7, 25 ],
        26 => [ 26, 27 ],
        27 => [ 26, 27 ],
        28 => [ 26, 28 ],
        29 => [ 29, 30 ],
        30 => [ 29, 30 ],
        31 => [ 31, 31 ],
        32 => [ 32, 32 ],
        33 => [ 33, 33 ],
        34 => [ 34, 34 ],
        35 => [ 35, 35 ],
        36 => [ 36, 36 ],
        37 => [ 37, 37, ],
        38 => [ 38, 38, ],
        39 => [ 39, 39, ],
        40 => [ 40, 40, ],
    ];

    public static function findGameData(
        string $name,
        string $locale,
    ): object {
        [$ClassID, $JobID] = self::findClassJob($name, $locale);

        $className = match ($locale) {
            LocaleEnum::FR->value => ClassJobsFrench::ROLES[$ClassID],
            LocaleEnum::DE->value => ClassJobsGerman::ROLES[$ClassID],
            LocaleEnum::JA->value => ClassJobsJapanese::ROLES[$ClassID],
            default => self::ROLES[$ClassID],
        };

        $jobName = match ($locale) {
            LocaleEnum::EN->value => self::ROLES[$JobID],
            LocaleEnum::FR->value => ClassJobsFrench::ROLES[$JobID],
            LocaleEnum::DE->value => ClassJobsGerman::ROLES[$JobID],
            LocaleEnum::JA->value => ClassJobsJapanese::ROLES[$JobID],
            default => null,
        };

        return (object)[
            'Name'    => "{$className} / {$jobName}",
            'ClassID' => self::CLASS_JOB_LINKS[$ClassID][0],
            'JobID'   => self::CLASS_JOB_LINKS[$JobID][1] ?? null
        ];
    }

    /**
     * Provides the correct role ID for a given role name, this
     * separates job/class.
     */
    public static function findRoleIdFromName($name)
    {
        $name = strtolower($name);
        $array = array_flip(self::ROLES);

        return $array[$name] ?? null;
    }

    /**
     * Find class/job in the json data
     *
     * @return bool|object
     */
    private static function findClassJob(
        string $name,
        string $locale,
    ): false|array {
        foreach(self::CLASS_JOB_LINKS as $classjob) {
            [$ClassID, $JobID] = $classjob;

            $className = match ($locale) {
                LocaleEnum::EN->value => self::ROLES[$ClassID],
                LocaleEnum::FR->value => ClassJobsFrench::ROLES[$ClassID],
                LocaleEnum::DE->value => ClassJobsGerman::ROLES[$ClassID],
                LocaleEnum::JA->value => ClassJobsJapanese::ROLES[$ClassID],
                default => null,
            };

            $jobName = match ($locale) {
                LocaleEnum::EN->value => self::ROLES[$JobID],
                LocaleEnum::FR->value => ClassJobsFrench::ROLES[$JobID],
                LocaleEnum::DE->value => ClassJobsGerman::ROLES[$JobID],
                LocaleEnum::JA->value => ClassJobsJapanese::ROLES[$JobID],
                default => null,
            };

            if (
                ($className && self::minifyName($name) === self::minifyName($className)) ||
                ($jobName && self::minifyName($name) === self::minifyName($jobName))
            ) {
                return $classjob;
            }
        }

        return false;
    }

    /**
     * @param $name
     * @return string
     */
    private static function minifyName($name): string
    {
        return mb_strtolower(trim(str_ireplace(" ", null, $name)), 'UTF-8');
    }
}
