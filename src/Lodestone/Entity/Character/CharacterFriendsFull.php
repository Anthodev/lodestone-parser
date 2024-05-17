<?php

namespace Lodestone\Entity\Character;

use Lodestone\Entity\AbstractEntity;
use Lodestone\Entity\ListView\ListView;
use Lodestone\Entity\LodestoneDataInterface;

class CharacterFriendsFull extends AbstractEntity implements LodestoneDataInterface
{
    public $ID;
    /** @var array */
    public $Members = [];

    public function addMembers(ListView $list)
    {
        $this->Members = array_merge(
            $this->Members,
            $list->Results
        );
    }
}
