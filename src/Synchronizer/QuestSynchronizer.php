<?php

namespace App\Synchronizer;

use App\Entity\Quest\Quest;
use App\Entity\Quest\QuestItem;
use App\Entity\Quest\QuestMonster;
use App\Entity\Quest\QuestMonsterArea;
use App\Entity\Quest\QuestMonsterAttribute;
use App\Entity\Quest\QuestMonsterSize;
use App\Enum\Quest\QuestItemType;
use App\Enum\Quest\QuestType;
use App\Model\Crawler\BaseCrawler;
use App\Synchronizer\Enum\QuestSelector;
use App\Utils\CrawlerUtils;
use App\Utils\Utils;

class QuestSynchronizer extends AbstractSynchronizer
{
    private const int BATCH_SIZE = 50;
    private const string ITEMS_LIST_PATH = 'data/quests';

    public function synchronize(): void
    {
        $this->ping();
        $this->helper()->disableSQLLog();
        $this->helper()->cleanEntitiesData( // FIXME cascade
            QuestMonsterArea::class,
            QuestMonsterSize::class,
            QuestMonsterAttribute::class,
            QuestMonster::class,
            QuestItem::class,
            Quest::class
        );

        foreach (QuestType::cases() as $type) {
            $this->synchronizeType($type);
        }
    }

    private function synchronizeType(QuestType $type): void
    {
        $this->logger()->debug(\sprintf('>>> Quest : start sync %s', \strtolower($type->label())));

        $url = \sprintf('%s?view=%s', $this->getListUrl(), $type->kiranicoView());
        $crawler = new BaseCrawler($url);

        $nodes = $crawler->findNodesBySelector(QuestSelector::LIST_A->value);
        $crawler->clear();

        foreach ($nodes as $i => $node) {
            $this->synchronizeQuest($node, $type);

            if (0 === $i % self::BATCH_SIZE) {
                $this->logger()->info(\sprintf('... ... ... %d / %d', $i, $nodes->count()));
                $this->flushAndClear();
            }
        }

        $this->logger()->info(\sprintf('... ... ... %d / %d', $nodes->count(), $nodes->count()));
        $this->logger()->info(Utils::getMemoryConsumption());
        $this->flushAndClear();
    }

    private function synchronizeQuest(\DOMNode $node, QuestType $type): void
    {
        $href = CrawlerUtils::findAttributeByName($node, 'href');
        if (null === $href) {
            return; // unprocessable
        }

        $crawler = new BaseCrawler($href);
        $titleH1 = $crawler->findCurrentNodeBySelector(QuestSelector::DETAIL_TITLE_H1->value);

        if (null === $titleH1?->textContent) {
            return; // unprocessable
        }

        $quest = new Quest();
        $quest->setType($type);
        $quest->setName(Utils::cleanString($titleH1->textContent));

        $this->synchronizeClientAndDescription($quest, $crawler);
        $this->synchronizeObjectiveHrpMrp($quest, $crawler);
        $this->synchronizeFailureConditions($quest, $crawler);
        $this->synchronizeMonsters($quest, $crawler);
        $this->synchronizeItems($quest, $crawler);

        $crawler->clear();
        unset($crawler);

        $this->em()->persist($quest);
    }

    private function synchronizeClientAndDescription(Quest $quest, BaseCrawler $crawler): void
    {
        $descriptionAndClientP = $crawler->findCurrentNodeBySelector(QuestSelector::DETAIL_DESCRIPTION_P->value);
        $descriptionAndClient = \explode(':', $descriptionAndClientP?->textContent ?? '', 2);

        $clientName = $descriptionAndClient[0] ?? null;
        if (!empty($clientName)) {
            $client = $this->cache()->findClient($clientName);
            $quest->setClient($client);
        }

        $description = $descriptionAndClient[1] ?? null;
        $quest->setDescription($description ? Utils::cleanString($description) : null);
    }

    private function synchronizeObjectiveHrpMrp(Quest $quest, BaseCrawler $crawler): void
    {
        $objectiveHrpMrpDiv = $crawler
            ->findCurrentNodeBySelector(QuestSelector::DETAIL_OBJECTIVE_HRP_MRP_FAILURE_CONDITIONS_DIV->value);

        $content = $objectiveHrpMrpDiv?->textContent ? Utils::cleanString($objectiveHrpMrpDiv->textContent) : null;
        if (null === $content) {
            return; // unprocessable part
        }

        if (\preg_match('/^(.*?) \(HRP: ([\d,]+)pts \| MRP: ([\d,]+)pts\)$/', $content, $matches)) {
            $quest->setObjective(!empty($matches[1]) ? Utils::cleanString($matches[1]) : null);
            $quest->setHrp(\intval(\str_replace(',', '', Utils::cleanString($matches[2]))));
            $quest->setMrp(\intval(\str_replace(',', '', Utils::cleanString($matches[3]))));
        } elseif (\preg_match('/HRP: ([\d,]+)pts \| MRP: ([\d,]+)pts/', $content, $matches)) {
            $quest->setHrp(\intval(\str_replace(',', '', Utils::cleanString($matches[1]))));
            $quest->setMrp(\intval(\str_replace(',', '', Utils::cleanString($matches[2]))));
        }
    }

    private function synchronizeFailureConditions(Quest $quest, BaseCrawler $crawler): void
    {
        $failureConditionDiv = $crawler
            ->findNodeBySelectorAndKey(QuestSelector::DETAIL_OBJECTIVE_HRP_MRP_FAILURE_CONDITIONS_DIV->value, 1);
        $failureCondition = $failureConditionDiv?->textContent ?
            Utils::cleanString($failureConditionDiv->textContent) : null;

        if (null === $failureCondition) {
            return; // unprocessable part
        }

        $quest->setFailureConditions(Utils::cleanString($failureCondition));
    }

    private function synchronizeMonsters(Quest $quest, BaseCrawler $crawler): void
    {
        $this->synchronizeMonstersAttributes($quest, $crawler);
        $this->synchronizeMonstersSizesAndAreas($quest, $crawler);
    }

    private function synchronizeMonstersAttributes(Quest $quest, BaseCrawler $crawler): void
    {
        $monstersTbody = $crawler->findCurrentNodeBySelector(QuestSelector::DETAIL_TABLE_TBODY->value);
        if (null === $monstersTbody) {
            return; // unprocessable
        }

        /** @var \DOMNode $childNode */
        foreach ($monstersTbody->childNodes as $childNode) {
            if (!CrawlerUtils::is($childNode, 'tr')) {
                continue;
            }

            $this->synchronizeMonsterAttributes($quest, $childNode);
        }

        unset($monstersTbody);
    }

    private function synchronizeMonsterAttributes(Quest $quest, \DOMNode $node): void
    {
        $key = 0;
        $attribute = new QuestMonsterAttribute();

        /** @var \DOMNode $childNode */
        foreach ($node->childNodes as $childNode) {
            if (!CrawlerUtils::is($childNode, 'td')) {
                continue;
            }

            $value = Utils::cleanString($childNode->textContent);

            if (0 === $key) {
                $monster = $this->cache()->findMonster($value);
                if (null === $monster) {
                    return; // unprocessable
                }

                $questMonster = $quest->findOrCreateMonster($monster);
                $questMonster->addAttribute($attribute);

                $this->em()->persist($questMonster);
            }

            match ($key) {
                1 => $attribute->setNbPlayers(\intval($value)),
                2 => $attribute->setHps($this->helper()->transformMonsterAttributeHps($value)),
                3 => $attribute->setAttack($value),
                4 => $attribute->setParts($value),
                5 => $attribute->setDefense(\intval($value)),
                6 => $attribute->setAilment($value),
                7 => $attribute->setStun($value),
                8 => $attribute->setStamina($value),
                9 => $attribute->setMount($value),
                default => null,
            };

            ++$key;
        }
    }

    private function synchronizeMonstersSizesAndAreas(Quest $quest, BaseCrawler $crawler): void
    {
        /** @var array<int, \DOMNode> $monstersNamesAnchor */
        $monstersNamesAnchor = $crawler->findNodesBySelector(QuestSelector::DETAIl_SIZES_AREAS_MONSTER_NAME_ANCHOR->value);
        foreach ($monstersNamesAnchor as $key => $monsterNamesAnchor) {
            $monsterName = $monsterNamesAnchor->textContent;
            $monster = $this->cache()->findMonster($monsterName);
            if (null === $monster) {
                continue; // unprocessable
            }

            $questMonster = $quest->findMonster($monster);
            if (null === $questMonster) {
                continue; // unprocessable
            }

            $this->synchronizeMonsterSizes($questMonster, $crawler, $key);
            $this->synchronizeMonsterAreas($questMonster, $crawler, $key);
        }
    }

    private function synchronizeMonsterSizes(QuestMonster $questMonster, BaseCrawler $crawler, int $position): void
    {
        $monsterSizesTbody = $crawler->findNodeBySelectorAndKey(QuestSelector::DETAIL_SIZES_TBODY->value, $position);
        if (null === $monsterSizesTbody) {
            return; // unprocessable
        }

        /** @var \DOMNode $tr */
        foreach ($monsterSizesTbody->childNodes as $tr) {
            if (!CrawlerUtils::is($tr, 'tr')) {
                continue;
            }

            $key = 0;
            $size = new QuestMonsterSize();
            foreach ($tr->childNodes as $td) {
                if (!CrawlerUtils::is($td, 'td')) {
                    continue;
                }

                $value = Utils::cleanString($td->textContent);
                match ($key) {
                    0 => $size->setSize(\str_replace(',', '', $value)),
                    1 => $size->setPercentChance(\intval(\str_replace('%', '', $value))),
                    default => ''
                };

                ++$key;
            }

            $questMonster->addSize($size);
        }

        unset($monsterSizesTbody);
    }

    private function synchronizeMonsterAreas(QuestMonster $questMonster, BaseCrawler $crawler, int $position): void
    {
        $monsterAreasTbody = $crawler->findNodeBySelectorAndKey(QuestSelector::DETAIL_AREAS_TBODY->value, $position);
        if (null === $monsterAreasTbody) {
            return; // unprocessable
        }

        /** @var \DOMNode $tr */
        foreach ($monsterAreasTbody->childNodes as $tr) {
            if (!CrawlerUtils::is($tr, 'tr')) {
                continue;
            }

            $key = 0;
            $area = new QuestMonsterArea();
            foreach ($tr->childNodes as $td) {
                if (!CrawlerUtils::is($td, 'td')) {
                    continue;
                }

                $value = Utils::cleanString($td->textContent);
                match ($key) {
                    0 => $area->setNumero(\intval(\str_replace('Area ', '', $value))),
                    1 => $area->setPercentChance(\intval(\str_replace('%', '', $value))),
                    default => ''
                };

                ++$key;
            }

            $questMonster->addArea($area);
        }

        unset($monsterAreasTbody);
    }

    private function synchronizeItems(Quest $quest, BaseCrawler $crawler): void
    {
        $rewardTbody = $crawler->findNodeBySelectorAndKey(QuestSelector::DETAIL_ITEMS_TBODY->value, 0);
        if (null === $rewardTbody) {
            return; // unprocessable
        }

        /** @var \DOMNode $childNode */
        foreach ($rewardTbody->childNodes as $childNode) {
            if (!CrawlerUtils::is($childNode, 'tr')) {
                continue;
            }

            $this->synchronizeItem($quest, $childNode, QuestItemType::REWARD);
        }

        $supplyBoxTbody = $crawler->findNodeBySelectorAndKey(QuestSelector::DETAIL_ITEMS_TBODY->value, 1);
        if (null === $supplyBoxTbody) {
            return; // unprocessable
        }

        /** @var \DOMNode $childNode */
        foreach ($supplyBoxTbody->childNodes as $childNode) {
            if (!CrawlerUtils::is($childNode, 'tr')) {
                continue;
            }

            $this->synchronizeItem($quest, $childNode, QuestItemType::SUPPLY_BOX);
        }
    }

    private function synchronizeItem(Quest $quest, \DOMNode $node, QuestItemType $type): void
    {
        $key = 0;
        $questItem = new QuestItem();
        $questItem->setType($type);

        /** @var \DOMNode $childNode */
        foreach ($node->childNodes as $childNode) {
            if (!CrawlerUtils::is($childNode, 'td')) {
                continue;
            }

            $value = Utils::cleanString($childNode->textContent);
            if (0 === $key && empty($value)) {
                return; // unprocessable
            }

            if (0 === $key) {
                $item = $this->cache()->findItem($value);
                if (null === $item) {
                    continue; // unprocessable
                }

                $questItem->setItem($item);
            }

            if (QuestItemType::REWARD === $questItem->getType()) {
                match ($key) {
                    2 => $questItem->setQuantity(\intval(\str_replace('x', '', $value))),
                    3 => $questItem->setPercentChance(\intval(\str_replace('%', '', $value))),
                    default => ''
                };
            } elseif (QuestItemType::SUPPLY_BOX === $questItem->getType()) {
                match ($key) {
                    1 => $questItem->setQuantity(\intval(\str_replace('x', '', $value))),
                    default => ''
                };
            }

            ++$key;
        }

        $this->em()->persist($questItem);
        $quest->addItem($questItem);
    }

    private function getListUrl(): string
    {
        return \sprintf('%s/%s', $this->getKiranicoUrl(), self::ITEMS_LIST_PATH);
    }
}
