<?php

namespace Lodestone\Parser;

use Lodestone\Entity\Character\ClassJob;
use Lodestone\Entity\Character\ClassJobBozjan;
use Lodestone\Entity\Character\ClassJobEureka;
use Lodestone\Enum\LocaleEnum;
use Lodestone\Exceptions\LodestonePrivateException;
use Lodestone\Game\ClassJobs;
use LodestoneUtils\Translator;
use Rct567\DomQuery\DomQuery;

class ParseCharacterClassJobs extends ParseAbstract implements Parser
{
    use HelpersTrait;

    /**
     * @return array<string, mixed>
     */
    public function handle(
        string $htmlContent,
        string $locale = LocaleEnum::EN->value,
    ): array {
        // set dom
        $this->setDom($htmlContent);

        $classjobs = [];

        // loop through roles
        /** @var DomQuery $li */
        foreach ($this->dom->find('.character__content')->find('li') as $li)
        {
            // class name
            $name   = trim($li->find('.character__job__name')->text());
            $master = trim($li->find('.character__job__name--meister')->text());
            $name   = str_ireplace(Translator::translate($locale, 'Limited Job'), null, $name);
            $name   = $name ?: $master;

            if (empty($name)) {
                continue;
            }

            // get game data ids
            $gd = ClassJobs::findGameData($name, $locale);

            // build role
            $role          = new ClassJob();
            $role->Name    = $gd->Name;
            $role->ClassID = $gd->ClassID;
            $role->JobID   = $gd->JobID;

            // Handle the unlock state based on the tooltip name, the 1st "Name" will be Job or Class if no Job unlocked.
            $unlockedState = trim(explode('/', $li->find('.character__job__name')->attr('data-tooltip'))[0]);

            $role->UnlockedState = [
                'Name' => $unlockedState,
                'ID' => ClassJobs::findRoleIdFromName($unlockedState)
            ];

            // level
            $level = trim($li->find('.character__job__level')->text());
            $level = ($level === '-') ? 0 : (int) $level;
            $role->Level = $level;

            //specialist
            $role->IsSpecialised = !empty($li->find('.character__job__name--meister')->text());

            // current exp
            [$current, $max] = explode('/', $li->find('.character__job__exp')->text());
            $current = filter_var(trim(str_ireplace('-', null, $current)) ?: 0, FILTER_SANITIZE_NUMBER_INT);
            $max     = filter_var(trim(str_ireplace('-', null, $max)) ?: 0, FILTER_SANITIZE_NUMBER_INT);

            $role->ExpLevel     = $current;
            $role->ExpLevelMax  = $max;
            $role->ExpLevelTogo = $max - $current;

            $classjobs[] = $role;
        }

        $elementalIndex = 1;

        /** @var DomQuery $node */

        //
        // Bozjan Southern Front
        //
        $bozjan          = new ClassJobBozjan(Translator::translate($locale, 'Resistance Rank'));
        $node            = $this->dom->find('.character__job__list')[0];
        $fieldname       = trim($node->find('.character__job__name')->text());

        // if elemental level is the 1st one, they haven't started Bozjan
        if ($fieldname === Translator::translate($locale, 'Elemental Level')) {
            $elementalIndex = 0;
        } else {
            $bozjanString    = trim($node->find('.character__job__exp')->text());

            if ($bozjanString) {
                [$current, $max] = explode('/', $bozjanString);
                $current         = filter_var(trim(str_ireplace('-', null, $current)) ?: 0, FILTER_SANITIZE_NUMBER_INT);
                //If rank is max (25) then set current Mettle to null instead of an empty string ("")
                if ($current == "") {
                    $current = null;
                }


                $bozjan->Level        = (int)$node->find('.character__job__level')->text();
                $bozjan->Mettle       = $current;
            }
        }

        //
        // The Forbidden Land, Eureka
        //
        $elemental       = new ClassJobEureka(Translator::translate($locale, 'Elemental Level'));
        $node            = $this->dom->find('.character__job__list')[$elementalIndex];

        $eurekaString    = explode('/', $node->find('.character__job__exp')->text());
        $current         = $eurekaString[0] ?? null;
        $max             = $eurekaString[1] ?? null;

        $current         = filter_var(trim(str_ireplace('-', null, $current)) ?: 0, FILTER_SANITIZE_NUMBER_INT);
        $max             = filter_var(trim(str_ireplace('-', null, $max)) ?: 0, FILTER_SANITIZE_NUMBER_INT);

        $elemental->Level        = (int)$node->find('.character__job__level')->text();
        $elemental->ExpLevel     = $current;
        $elemental->ExpLevelMax  = $max;
        $elemental->ExpLevelTogo = $max - $current;

        // fin

        unset($box);
        unset($node);

        return [
            'classjobs' => $classjobs,
            'elemental' => $elemental,
            'bozjan'    => $bozjan,
        ];
    }
}
