<?php

namespace Lodestone\Entity\FreeCompany;

use Lodestone\Entity\AbstractEntity;
use Lodestone\Entity\ListView\ListView;

class FreeCompanyFull extends AbstractEntity
{
    public int $ID;
    public FreeCompany $Profile;
    public array $Members = [];

    public function addMembers(ListView $list)
    {
        $this->Members = array_merge(
            $this->Members,
            $list->Results
        );
    }
}
