<?php

namespace App\Synchronizer;

use App\Entity\Monster\Monster;
use App\Entity\Monster\MonsterAilmentEffectiveness;
use App\Entity\Monster\MonsterBodyPart;
use App\Entity\Monster\MonsterBodyPartWeakness;
use App\Entity\Monster\MonsterItem;
use App\Enum\Equipment\Weapon\Extract;
use App\Enum\Item\ItemDropMethod;
use App\Enum\Monster\MonsterType;
use App\Enum\Quest\QuestRank;
use App\Enum\StatusEffect;
use App\Model\Crawler\BaseCrawler;
use App\Synchronizer\Enum\MonsterSelector;
use App\Utils\CrawlerUtils;
use App\Utils\Utils;

class MonsterSynchronizer extends AbstractSynchronizer
{
    public const string ITEMS_LIST_PATH = 'data/monsters';
    private const int BATCH_SIZE = 25;

    public function synchronize(): void
    {
        $this->ping();
        $this->helper()->disableSQLLog();
        $this->helper()->cleanEntitiesData(
            MonsterAilmentEffectiveness::class,
            MonsterBodyPartWeakness::class,
            MonsterBodyPart::class,
            MonsterItem::class,
            Monster::class
        );

        foreach (MonsterType::cases() as $type) {
            $this->synchronizeType($type);
        }
    }

    private function synchronizeType(MonsterType $type): void
    {
        $crawler = new BaseCrawler(\sprintf('%s?view=%s', $this->getListUrl(), $type->value));
        $nodes = $crawler->findNodesBySelector(MonsterSelector::LIST_DIV->value);

        $this->startProgressBar($nodes->count(), \sprintf('Monster > "%s"', $type->label()));
        foreach ($nodes as $i => $node) {
            $this->synchronizeMonster($node, $type);
            $this->advanceProgressBar();

            if (0 === $i % self::BATCH_SIZE) {
                $this->flushAndClear();
            }
        }

        $this->flushAndClear();
        $this->finishProgressBar();
    }

    private function synchronizeMonster(\DOMNode $node, MonsterType $type): void
    {
        $listCrawler = new BaseCrawler($node);

        $a = $listCrawler->findCurrentNodeBySelector(MonsterSelector::LIST_A->value);
        if (null === $a) {
            return; // unprocessable
        }

        $href = CrawlerUtils::findAttributeByName($a, 'href');
        if (null === $href) {
            return; // unprocessable
        }

        $crawler = new BaseCrawler($href);

        $monster = new Monster();
        $monster->setType($type);

        $this->synchronizeName($monster, $crawler);
        $this->synchronizeDescription($monster, $crawler);
        $this->synchronizeImagesUrls($monster, $listCrawler);
        $this->synchronizePhysiology($monster, $crawler);
        $this->synchronizeAilmentsEffectiveness($monster, $crawler);
        $this->synchronizeItems($monster, $crawler);

        $this->em()->persist($monster);
    }

    private function synchronizeName(Monster $monster, BaseCrawler $crawler): void
    {
        $nameH1 = $crawler->findCurrentNodeBySelector(MonsterSelector::DETAIL_NAME_H1->value);
        if (null === $nameH1) {
            return; // unprocessable;
        }

        $name = Utils::cleanString($nameH1->textContent);
        $monster->setName($name);
    }

    private function synchronizeDescription(Monster $monster, BaseCrawler $crawler): void
    {
        $descriptionP = $crawler->findCurrentNodeBySelector(MonsterSelector::DETAIL_DESCRIPTION_P->value);
        if (null === $descriptionP) {
            return; // unprocessable;
        }

        $description = Utils::cleanString($descriptionP->textContent);
        $monster->setDescription($description);
    }

    private function synchronizeImagesUrls(Monster $monster, BaseCrawler $crawler): void
    {
        $imageImg = $crawler->findCurrentNodeBySelector(MonsterSelector::LIST_IMG->value);
        if (null === $imageImg) {
            return;
        }

        $src = CrawlerUtils::findAttributeByName($imageImg, 'src');
        $monster->setImageUrl($src);
    }

    private function synchronizePhysiology(Monster $monster, BaseCrawler $crawler): void
    {
        // 1. body part => weakness
        $physiology1Tbody = $crawler->findNodeBySelectorAndKey(MonsterSelector::DETAIL_TABLE_TBODY->value, 0);
        if (null === $physiology1Tbody) {
            return; // unprocessable
        }

        /** @var \DOMNode $childNode */
        foreach ($physiology1Tbody->childNodes as $childNode) {
            if (!CrawlerUtils::is($childNode, 'tr')) {
                continue;
            }

            $this->synchronizeBodyPart1($monster, $childNode);
        }

        // 2. body part => hp + extract
        $physiology2Tbody = $crawler->findNodeBySelectorAndKey(MonsterSelector::DETAIL_TABLE_TBODY->value, 1);
        if (null === $physiology2Tbody) {
            return; // unprocessable
        }

        /** @var \DOMNode $childNode */
        foreach ($physiology2Tbody->childNodes as $childNode) {
            if (!CrawlerUtils::is($childNode, 'tr')) {
                continue;
            }

            $this->synchronizeBodyPart2($monster, $childNode);
        }
    }

    private function synchronizeBodyPart1(Monster $monster, \DOMNode $node): void
    {
        $key = 0;
        $weakness = new MonsterBodyPartWeakness();

        /** @var \DOMNode $childNode */
        foreach ($node->childNodes as $childNode) {
            if (!CrawlerUtils::is($childNode, 'td')) {
                continue;
            }

            $value = Utils::cleanString($childNode->textContent);
            if (0 === $key && empty($value)) { // part is empty
                return; // unprocessable
            }

            // 0 => get or create body part, and create body part weakness object
            if (0 === $key) {
                $part = $monster->findBodyPartByName($value) ?? new MonsterBodyPart($value);
                $part->addWeakness($weakness);
                $monster->addBodyPart($part);
            }

            // other => map weakness's data
            match ($key) {
                1 => $weakness->setState((int) $value),
                2 => $weakness->setHitSlash((int) $value),
                3 => $weakness->setHitStrike((int) $value),
                4 => $weakness->setHitShell((int) $value),
                5 => $weakness->setElementFire((int) $value),
                6 => $weakness->setElementWater((int) $value),
                7 => $weakness->setElementIce((int) $value),
                8 => $weakness->setElementThunder((int) $value),
                9 => $weakness->setElementDragon((int) $value),
                10 => $weakness->setElementStun((int) $value),
                default => null,
            };

            ++$key;
        }
    }

    private function synchronizeBodyPart2(Monster $monster, \DOMNode $node): void
    {
        $part = null;
        $key = 0;

        /** @var \DOMNode $childNode */
        foreach ($node->childNodes as $childNode) {
            if (!CrawlerUtils::is($childNode, 'td')) {
                continue;
            }

            $value = Utils::cleanString($childNode->textContent);
            if (0 === $key && empty($value)) { // part is empty
                return; // unprocessable
            }

            if (0 === $key) {
                $part = $monster->findBodyPartByName($value);
            }

            if (null === $part) {
                return; // unprocessable
            }

            match ($key) {
                1 => $part->setHp((int) $value),
                3 => $part->setExtract(Extract::tryFrom(\strtolower($value))),
                default => null
            };

            ++$key;
        }
    }

    private function synchronizeAilmentsEffectiveness(Monster $monster, BaseCrawler $crawler): void
    {
        $ailmentsTbody = $crawler->findNodeBySelectorAndKey(MonsterSelector::DETAIL_TABLE_TBODY->value, 3);
        if (null === $ailmentsTbody) {
            return; // unprocessable
        }

        /** @var \DOMNode $childNode */
        foreach ($ailmentsTbody->childNodes as $childNode) {
            if (!CrawlerUtils::is($childNode, 'tr')) {
                continue;
            }

            $this->synchronizeAilmentEffectiveness($monster, $childNode);
        }
    }

    private function synchronizeAilmentEffectiveness(Monster $monster, \DOMNode $node): void
    {
        $ailmentEff = null;
        $key = 0;

        /** @var \DOMNode $childNode */
        foreach ($node->childNodes as $childNode) {
            if (!CrawlerUtils::is($childNode, 'td')) {
                continue;
            }

            $value = Utils::cleanString($childNode->textContent);
            $ailment = StatusEffect::tryFrom(\strtolower($value));
            if (0 === $key && null === $ailment) { // part is empty
                return; // unprocessable
            }

            // 0 => get or create ailment effectiveness
            if (0 === $key) {
                $ailmentEff = $monster->findAilmentEffectiveness($ailment) ?? new MonsterAilmentEffectiveness($ailment);
                $monster->addAilmentsEffectiveness($ailmentEff);
            }

            if (null === $ailmentEff) {
                return; // unprocessable
            }

            // other => map ailment effectiveness's data
            match ($key) {
                1 => $ailmentEff->setBuildup(Utils::replaceMultipleSpacesByOne($value)),
                2 => $ailmentEff->setDecay(Utils::replaceMultipleSpacesByOne($value)),
                3 => $ailmentEff->setDamage((int) $value),
                4 => $ailmentEff->setDuration($value),
                default => null,
            };

            ++$key;
        }
    }

    private function synchronizeItems(Monster $monster, BaseCrawler $crawler): void
    {
        $itemsTbdoy = $crawler->findNodeBySelectorAndKey(MonsterSelector::DETAIL_TABLE_TBODY->value, 5);
        if (null === $itemsTbdoy) {
            return; // unprocessable
        }

        /** @var \DOMNode $childNode */
        foreach ($itemsTbdoy->childNodes as $childNode) {
            if (!CrawlerUtils::is($childNode, 'tr')) {
                continue;
            }

            $this->synchronizeItem($monster, $childNode);
        }
    }

    private function synchronizeItem(Monster $monster, \DOMNode $node): void
    {
        $monsterItem = new MonsterItem();
        $key = 0;

        /** @var \DOMNode $childNode */
        foreach ($node->childNodes as $childNode) {
            if (!CrawlerUtils::is($childNode, 'td')) {
                continue;
            }

            $value = Utils::cleanString($childNode->textContent);

            if (0 === $key) {
                $item = $this->cache()->findItem($value);
                if (null === $item) {
                    return; // unprocessable
                }

                $monsterItem->setItem($item)->setMonster($monster);
                $monster->addItem($monsterItem);
            }

            match ($key) {
                1 => $monsterItem->setQuestRank(QuestRank::fromLabel($value)),
                2 => $monsterItem->setMethod(ItemDropMethod::fromLabel($value)),
                4 => $monsterItem->setAmount((int) \str_replace('x', '', $value)),
                5 => $monsterItem->setRate((int) \str_replace('%', '', $value)),
                default => null,
            };

            ++$key;
        }
    }

    private function getListUrl(): string
    {
        return \sprintf('%s/%s', $this->kiranicoUrl(), self::ITEMS_LIST_PATH);
    }

    public static function getDefaultPriority(): int
    {
        return 90;
    }
}
