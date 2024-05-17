<?php

namespace Lodestone\Entity\Character;

use Lodestone\Entity\AbstractEntity;
use Lodestone\Entity\LodestoneDataInterface;

class Achievements extends AbstractEntity implements LodestoneDataInterface
{
    /** @var Achievement[] */
    public array $Achievements = [];
    public int $ParseDate;

    public function __construct()
    {
        $this->ParseDate = time();
    }
}
