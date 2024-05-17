<?php

namespace Lodestone\Entity\Character;

use Lodestone\Entity\AbstractEntity;
use Lodestone\Entity\LodestoneDataInterface;

class Mount extends AbstractEntity implements LodestoneDataInterface
{
    public $Name;
    public $Icon;
}
