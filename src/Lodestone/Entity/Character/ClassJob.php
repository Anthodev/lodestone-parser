<?php

namespace Lodestone\Entity\Character;

use Lodestone\Entity\AbstractEntity;

class ClassJob extends AbstractEntity
{
    public string $Name;
    public int $ClassID;
    public int $JobID;
    public int $Level;
    public int $ExpLevel;
    public int $ExpLevelTogo;
    public int $ExpLevelMax;
    public bool $IsSpecialised;
    public array $UnlockedState;
}
