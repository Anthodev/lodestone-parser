<?php

namespace Lodestone\Entity\FreeCompany;

use Lodestone\Entity\AbstractEntity;

class FreeCompanyMembers extends AbstractEntity
{
    public int $ID;
    public int $ParseDate;

    public function __construct()
    {
        $this->ParseDate = time();
    }
}
