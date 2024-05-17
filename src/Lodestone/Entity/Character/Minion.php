<?php

namespace Lodestone\Entity\Character;

use Lodestone\Entity\AbstractEntity;
use Lodestone\Entity\LodestoneDataInterface;

class Minion extends AbstractEntity implements LodestoneDataInterface
{
    public $Name;
    public $Icon;
}
