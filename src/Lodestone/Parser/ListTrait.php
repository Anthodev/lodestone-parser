<?php

namespace Lodestone\Parser;

use Lodestone\Entity\Character\CharacterSimple;
use Lodestone\Entity\ListView\ListView;
use Lodestone\Enum\LocaleEnum;
use LodestoneUtils\Translator;
use Rct567\DomQuery\DomQuery;

trait ListTrait
{
    protected ListView $list;

    /**
     * Initialize a list view
     */
    protected function setList(string $locale = LocaleEnum::EN->value): void
    {
        $this->list = new ListView();

        if (empty($this->dom->find('.btn__pager__current')->html())) {
            return;
        }

        $data = $this->dom->find('.btn__pager__current')->text();

        if (str_contains($data, ' / ')) {
            [$current, $total] = explode(' / ', $data);
        } else {
            [$current, $total] = explode(Translator::translate($locale, ' of '), $data);
        }

        $this->list->Pagination->Page = filter_var($current, FILTER_SANITIZE_NUMBER_INT);
        $this->list->Pagination->PageTotal = filter_var($total, FILTER_SANITIZE_NUMBER_INT);

        // member count
        $count = $this->dom->find('.parts__total')->text();
        $this->list->Pagination->ResultsTotal = filter_var($count, FILTER_SANITIZE_NUMBER_INT);

        // set next+prev
        $this->list->Pagination->setNextPrevious();
    }

    public function handleCharacterList(): void
    {
        /** @var DomQuery $node */
        foreach ($this->dom->find('.ldst__window div.entry') as $node) {
            $obj         = new CharacterSimple();
            $obj->ID     = $this->getLodestoneId($node);
            $obj->Name   = $node->find('.entry__name')->text();
            $obj->Lang   = trim($node->find('.entry__chara__lang')->text());
            $obj->Server = trim(explode(' ', $node->find('.entry__world')->text())[0]);
            $obj->Avatar = $node->find('.entry__chara__face img')->attr('src');

            $this->list->Results[] = $obj;
        }
    }
}
