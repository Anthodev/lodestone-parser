<?php

namespace Lodestone\Entity\FreeCompany;

use Lodestone\Entity\AbstractEntity;
use Lodestone\Entity\LodestoneDataInterface;

class FreeCompany extends AbstractEntity implements LodestoneDataInterface
{
    public int $ID;
    public array $Crest = [];
    public string $GrandCompany;
    public string $Name;
    public string $Server;
    public string $DC;
    public string $Tag;
    public int $Formed;
    public int $ActiveMemberCount;
    public int $Rank;
    public array $Ranking;
    public string $Slogan;
    public array $Estate;
    public array $Reputation = [];
    public $Active;
    public $Recruitment;
    public array $Focus = [];
    public array $Seeking = [];
    public int $ParseDate;

    public function __construct()
    {
        $this->ParseDate = time();
    }
}
