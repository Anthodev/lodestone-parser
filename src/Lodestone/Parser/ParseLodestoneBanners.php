<?php

namespace Lodestone\Parser;

use Rct567\DomQuery\DomQuery;

class ParseLodestoneBanners extends ParseAbstract implements Parser
{
    use HelpersTrait;

    public function handle(string $htmlContent)
    {
        // set dom
        $this->setDom($htmlContent);

        /** @var DomQuery $node */
        $arr = [];
        foreach ($this->dom->find('#slider_bnr_area li') as $node) {
            $arr[] = [
                'Url'    => $node->find('a')->attr('href'),
                'Banner' => $node->find('img')->attr('src')
            ];
        }

        return $arr;
    }
}
