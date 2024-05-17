<?php

namespace Lodestone\Api;

use Lodestone\Entity\LodestoneDataInterface;
use Lodestone\Parser\ParseFreeCompany;
use Lodestone\Parser\ParseFreeCompanyMembers;
use Lodestone\Parser\ParseFreeCompanySearch;

class FreeCompany extends ApiAbstract
{
    public function search(
        string $name,
        string $server = null,
        int $page = 1
    ): LodestoneDataInterface {
        $name = str_ireplace(self::STRING_FIXES[0], self::STRING_FIXES[1], $name);

        /** @var LodestoneDataInterface */
        return $this->handle(ParseFreeCompanySearch::class, [
            'endpoint' => "/lodestone/freecompany",
            'query'    => [
                'q'         => '"'. $name .'"',
                'worldname' => $server,
                'page'      => $page
            ]
        ]);
    }

    public function get(
        string $id,
        string $locale,
    ): LodestoneDataInterface {
        /** @var LodestoneDataInterface */
        return $this->handle(
            parser: ParseFreeCompany::class,
            requestOptions: [
                'endpoint' => "/lodestone/freecompany/{$id}",
            ],
            locale: $locale,
        );
    }

    public function members(
        string $id,
        int $page = 1
    ): LodestoneDataInterface {
        /** @var LodestoneDataInterface */
        return $this->handle(ParseFreeCompanyMembers::class, [
            'endpoint' => "/lodestone/freecompany/{$id}/member",
            'query'    => [
                'page' => $page
            ]
        ]);
    }
}
