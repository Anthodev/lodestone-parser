<?php

namespace Lodestone\Entity\ListView;

use Lodestone\Entity\AbstractEntity;
use Lodestone\Entity\LodestoneDataInterface;

class ListView extends AbstractEntity implements LodestoneDataInterface
{
    /** @var Pagination */
    public $Pagination;
    /** @var array */
    public $Results = [];

    public function __construct()
    {
        $this->Pagination = new Pagination();
    }
}
