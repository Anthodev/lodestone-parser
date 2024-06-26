<?php

namespace Lodestone\Parser;

use Lodestone\Entity\Character\Achievement;
use Lodestone\Entity\Character\Achievements;
use Lodestone\Entity\Character\Minion;
use Lodestone\Exceptions\LodestonePrivateException;
use Rct567\DomQuery\DomQuery;

class ParseCharacterMinions extends ParseAbstract implements Parser
{
    use HelpersTrait;

    /**
     * @throws LodestonePrivateException
     */
    public function handle(string $htmlContent)
    {
        // set dom
        $this->setDom($htmlContent, true);
        $minions = [];
        foreach ($this->dom->find('.minion__list__item') as $li) {
            $minion       = new Minion();
            $minion->Name = $li->find('.minion__list__text')->text();
            $minion->Icon = $li->find('.minion__list__icon__image')->attr('data-original');
            $minions[]    = $minion;
        }
        return $minions;
    }
}
