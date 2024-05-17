<?php

namespace Lodestone\Entity\Character;

use Lodestone\Entity\AbstractEntity;

class CharacterSimple extends AbstractEntity
{
    public string $ID;
    public string $Name;
    public string $Lang;
    public string $Server;
    public string $Avatar;
    public string $Rank;
    public string $RankIcon;
    public int $FeastMatches = 0;
}
