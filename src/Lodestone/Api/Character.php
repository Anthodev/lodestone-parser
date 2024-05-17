<?php

namespace Lodestone\Api;

use Lodestone\Entity\LodestoneDataInterface;
use Lodestone\Parser\ParseCharacter;
use Lodestone\Parser\ParseCharacterAchievements;
use Lodestone\Parser\ParseCharacterClassJobs;
use Lodestone\Parser\ParseCharacterFollowing;
use Lodestone\Parser\ParseCharacterFriends;
use Lodestone\Parser\ParseCharacterMinions;
use Lodestone\Parser\ParseCharacterMounts;
use Lodestone\Parser\ParseCharacterSearch;

class Character extends ApiAbstract
{
    public function search(string $name, string $server = null, int $page = 1): LodestoneDataInterface
    {
        $name = str_ireplace(self::STRING_FIXES[0], self::STRING_FIXES[1], $name);

        /** @var LodestoneDataInterface */
        return $this->handle(ParseCharacterSearch::class, [
            'endpoint' => "/lodestone/character",
            'query'    => [
                'q'         => '"'. $name .'"',
                'worldname' => $server,
                'page'      => $page
            ]
        ]);
    }

    public function get(
        int $id,
        string $locale,
    ): LodestoneDataInterface {
        /** @var LodestoneDataInterface */
        return $this->handle(
            parser: ParseCharacter::class,
            requestOptions: [
                'endpoint' => "/lodestone/character/{$id}",
            ],
            locale: $locale,
        );
    }

    public function getFull(
        int $id,
        string $locale,
    ): LodestoneDataInterface {
        /** @var LodestoneDataInterface */
        return $this->handle(
            parser: ParseCharacter::class,
            requestOptions: [
                'endpoint' => "/lodestone/character/{$id}",
            ],
            extraRequestOptions: [
                'parser' => ParseCharacterClassJobs::class,
                'request' => [
                    'endpoint' => "/lodestone/character/{$id}/class_job",
                ],
                'dataTarget' => 'ClassJobs',
            ],
            locale: $locale,
        );
    }

    public function friends(int $id, int $page = 1): LodestoneDataInterface
    {
        /** @var LodestoneDataInterface */
        return $this->handle(ParseCharacterFriends::class, [
            'endpoint' => "/lodestone/character/{$id}/friend",
            'query'    => [
                'page' => $page,
            ],
        ]);
    }

    public function minions(int $id): array
    {
        /** @var LodestoneDataInterface[] */
        return $this->handle(ParseCharacterMinions::class, [
            'endpoint' => "/lodestone/character/{$id}/minion",
            'user-agent' => 'Mozilla/5.0 (Linux; Android 4.0.4; Galaxy Nexus Build/IMM76B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.76 Mobile Safari/537.36'
        ]);
    }

    public function mounts(int $id): array
    {
        /** @var LodestoneDataInterface[] */
        return $this->handle(ParseCharacterMounts::class, [
            'endpoint' => "/lodestone/character/{$id}/mount",
            'user-agent' => 'Mozilla/5.0 (Linux; Android 4.0.4; Galaxy Nexus Build/IMM76B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.76 Mobile Safari/537.36'
        ]);
    }

    public function classjobs(int $id)
    {
        return $this->handle(ParseCharacterClassJobs::class, [
            'endpoint' => "/lodestone/character/{$id}/class_job",
            //'user-agent' => 'Mozilla/5.0 (Linux; Android 4.0.4; Galaxy Nexus Build/IMM76B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.76 Mobile Safari/537.36'
        ]);
    }

    public function following(int $id, int $page = 1)
    {
        return $this->handle(ParseCharacterFollowing::class, [
            'endpoint' => "/lodestone/character/{$id}/following",
            'query'    => [
                'page' => $page
            ]
        ]);
    }

    public function achievements(
        int $id,
        string $locale,
        int $kindId = 1,
    ): LodestoneDataInterface {
        /** @var LodestoneDataInterface */
        return $this->handle(
            parser: ParseCharacterAchievements::class,
            requestOptions: [
                'endpoint' => "/lodestone/character/{$id}/achievement/kind/{$kindId}/",
            ],
            locale: $locale,
        );
    }
}
