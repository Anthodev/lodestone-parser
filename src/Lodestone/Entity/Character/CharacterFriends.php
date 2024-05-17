<?php

namespace Lodestone\Entity\Character;

use Lodestone\Entity\AbstractEntity;
use Lodestone\Entity\LodestoneDataInterface;

class CharacterFriends extends AbstractEntity implements LodestoneDataInterface
{
    public $ID;
    public $ParseDate;

    public function __construct()
    {
        $this->ParseDate = time();
    }
}
