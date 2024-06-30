<?php

namespace App\Synchronizer;

use App\Entity\Equipment\Armor\Armor;
use App\Entity\Equipment\Armor\ArmorMaterial;
use App\Entity\Equipment\Armor\ArmorResistance;
use App\Entity\Equipment\Armor\ArmorSlot;
use App\Enum\Equipment\EquipmentSlotType;
use App\Enum\Equipment\Skill\SkillType;
use App\Enum\StatusEffect;
use App\Model\Crawler\BaseCrawler;
use App\Synchronizer\Enum\ArmorSelector;
use App\Utils\CrawlerUtils;
use App\Utils\Utils;

class ArmorSynchronizer extends AbstractSynchronizer
{
    private const int BATCH_SIZE = 100;
    private const string ITEMS_LIST_PATH = 'data/armors';

    public function synchronize(): void
    {
        $this->ping();
        $this->helper()->disableSQLLog();
        $this->helper()->cleanEntitiesData(
            ArmorResistance::class,
            ArmorMaterial::class,
            ArmorSlot::class,
            Armor::class
        );

        foreach (\range(0, 9) as $view) {
            $this->synchronizeView($view);
        }
    }

    private function synchronizeView(int $view): void
    {
        $crawler = new BaseCrawler(\sprintf('%s?view=%d', $this->getListUrl(), $view));
        $nodes = $crawler->findNodesBySelector(ArmorSelector::LIST_ITEM_ANCHOR->value);

        $this->startProgressBar($nodes->count(), \sprintf('Armor > "Rarity %d"', $rarity = $view + 1));
        foreach ($nodes as $i => $node) {
            $this->synchronizeArmor($node, $rarity);
            $this->advanceProgressBar();

            if (0 === $i % self::BATCH_SIZE) {
                $this->flushAndClear();
            }
        }

        $this->flushAndClear();
        $this->finishProgressBar();
    }

    private function synchronizeArmor(\DOMNode $node, int $rarity): void
    {
        $href = CrawlerUtils::findAttributeByName($node, 'href');
        if (null === $href) {
            return; // unprocessable
        }

        $crawler = new BaseCrawler($href);
        $armor = new Armor();
        $armor->setRarity($rarity);

        $this->synchronizeName($armor, $crawler);
        $this->synchronizeImages($armor, $crawler);
        $this->synchronizeSlots($armor, $crawler);
        $this->synchronizeDefense($armor, $crawler);
        $this->synchronizeResistances($armor, $crawler);
        $this->synchronizeSkills($armor, $crawler);

        if ($this->isValid($armor)) {
            $this->em()->persist($armor);
        }
    }

    private function synchronizeName(Armor $armor, BaseCrawler $crawler): void
    {
        $nameH1 = $crawler->findCurrentNodeBySelector(ArmorSelector::DETAIL_H1->value);
        $name = $nameH1 ? Utils::cleanString($nameH1->textContent) : null;
        if (null === $name) {
            return; // unprocessable
        }

        $armor->setName($name);
    }

    private function synchronizeImages(Armor $armor, BaseCrawler $crawler): void
    {
        $imagesUrlsImg = $crawler->findNodesBySelector(ArmorSelector::DETAIL_IMG->value);
        foreach ($imagesUrlsImg as $image) {
            $src = CrawlerUtils::findAttributeByName($image, 'src');
            if (null === $src) {
                continue;
            }

            $armor->addImageUrl($src);
        }
    }

    private function synchronizeSlots(Armor $armor, BaseCrawler $crawler): void
    {
        $slotsImgs = $crawler->findNodesBySelector(ArmorSelector::DETAIL_SLOTS_IMG->value);

        /* @var \DOMNode $ailmentSpan */
        foreach ($slotsImgs as $slotsImg) {
            $src = CrawlerUtils::findAttributeByName($slotsImg, 'src');
            $quantity = $src ? $this->helper()->getSlotQuantityFromImageSrc($src) : null;
            if (null === $quantity) {
                continue; // unprocessable
            }

            $slot = new ArmorSlot();
            $slot->setType(EquipmentSlotType::BASIC);
            $slot->setQuantity($quantity);

            $armor->addSlot($slot);
        }
    }

    private function synchronizeDefense(Armor $armor, BaseCrawler $crawler): void
    {
        $defenseTd = $crawler->findCurrentNodeBySelector(ArmorSelector::DETAIL_DEFENSE_TD->value);
        $defense = $defenseTd ? Utils::cleanString(\str_replace(' Defense', '', $defenseTd->textContent)) : null;
        if (null === $defense) {
            return; // unprocessable
        }

        $armor->setDefense(\intval($defense));
    }

    private function synchronizeResistances(Armor $armor, BaseCrawler $crawler): void
    {
        $resistancesSpan = [];
        foreach (\range(3, 7) as $number) {
            $node = $crawler->findCurrentNodeBySelector(\sprintf(ArmorSelector::DETAIL_RESISTANCES_SPAN->value, $number));
            if (null == $node) {
                continue;
            }

            $resistancesSpan[] = $node;
        }

        /** @var \DOMNode $resistanceSpan */
        foreach ($resistancesSpan as $resistanceSpan) {
            $resistance = new ArmorResistance();
            $statusValueType = CrawlerUtils::findAttributeByName($resistanceSpan, 'data-value');
            $resistance->setElement(StatusEffect::tryFromValue(\intval($statusValueType)));

            /** @var \DOMNode $child */
            foreach ($resistanceSpan->childNodes as $child) {
                if (CrawlerUtils::is($child, 'span')) {
                    $resistance->setValue(\intval(Utils::cleanString($child->textContent)));
                }
            }

            $armor->addResistance($resistance);
        }
    }

    public function synchronizeSkills(Armor $armor, BaseCrawler $crawler): void
    {
        $skillsDivs = $crawler->findNodesBySelector(ArmorSelector::DETAIL_SKILLS_DIV->value);

        /** @var \DOMNode $skillDiv */
        foreach ($skillsDivs as $skillDiv) {
            $nameAndLevel = \explode('Lv ', Utils::cleanString($skillDiv->textContent));
            if (!isset($nameAndLevel[0]) || !isset($nameAndLevel[1])) {
                continue;
            }

            $level = $this->cache()->findSkillLevel(
                Utils::cleanString($nameAndLevel[0]),
                SkillType::BASIC,
                \intval($nameAndLevel[1])
            );
            if (null === $level) {
                continue;
            }

            $armor->addSkill($level);
        }
    }

    private function getListUrl(): string
    {
        return \sprintf('%s/%s', $this->kiranicoUrl(), self::ITEMS_LIST_PATH);
    }

    public static function getDefaultPriority(): int
    {
        return 50;
    }
}
