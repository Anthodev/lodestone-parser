<?php

namespace Lodestone\Parser;

use Lodestone\Entity\LodestoneDataInterface;
use Lodestone\Enum\LocaleEnum;

class ParseCharacterFriends extends ParseAbstract implements Parser
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
        $this->handleCharacterList();
        return $this->list;
    }
}
