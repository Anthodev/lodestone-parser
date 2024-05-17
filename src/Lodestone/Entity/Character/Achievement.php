<?php

namespace Lodestone\Entity\Character;

use Lodestone\Entity\AbstractEntity;

class Achievement extends AbstractEntity
{
    public int $ID;
    public string $Name;
    public string $Icon;
    public int $Points = 0;
    public int $ObtainedTimestamp;
}
