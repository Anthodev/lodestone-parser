<?php

namespace Lodestone\Entity\Character;

use Lodestone\Entity\AbstractEntity;
use Lodestone\Entity\LodestoneDataInterface;

class CharacterProfile extends AbstractEntity implements LodestoneDataInterface
{
    public string $ID;
    public string $Name;
    public string $Server;
    public string $DC;
    public string $Title;
    public bool $TitleTop;
    public string $Avatar;
    public string $Portrait;
    public ?string $Bio = '';
    public string $Race;
    public string $Tribe;
    public string $Gender;
    public string $Nameday;
    public object $GuardianDeity;
    public object $Town;
    public object $GrandCompany;
    public ?string $FreeCompanyId;
    public ?string $FreeCompanyName;
    public ?int $PvPTeamId;
    public array $ClassJobs = [];
    public array $GearSet = []; // gear + attributes
    public object $ActiveClassJob;
    public int $ParseDate;
    public string $Lang;

    public function __construct()
    {
        $this->ParseDate = time();
    }
}
