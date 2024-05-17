<?php

namespace Lodestone\Entity\FreeCompany;

use Lodestone\Entity\AbstractEntity;

class FreeCompanySimple extends AbstractEntity
{
    public string $ID;
    public string $Name;
    public string $Server;
    public array $Crest = [];
}
