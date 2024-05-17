<?php

namespace Lodestone\Parser;

use Lodestone\Entity\FreeCompany\FreeCompanySimple;
use Lodestone\Entity\LodestoneDataInterface;
use Lodestone\Enum\LocaleEnum;
use Rct567\DomQuery\DomQuery;

class ParseLinkshellSearch extends ParseAbstract implements Parser
{
    use HelpersTrait;
    use ListTrait;

    public function handle(
        string $htmlContent,
        string $locale = LocaleEnum::EN->value,
    ): LodestoneDataInterface {
        // set dom
        $this->setDom($htmlContent);

        // build list
        $this->setList($locale);

        // parse list
        /** @var DomQuery $node */
        foreach ($this->dom->find('.ldst__window div.entry') as $node) {
            $obj         = new FreeCompanySimple();
            $obj->ID     = $this->getLodestoneId($node);
            $obj->Name   = $node->find('.entry__name')->text();
            $obj->Server = trim(explode(' ', $node->find('.entry__world')->text())[0]);

            $this->list->Results[] = $obj;
        }

        return $this->list;
    }
}
