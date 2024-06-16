<?php

namespace App\Synchronizer;

use App\Entity\Weapon\Weapon;
use App\Entity\Weapon\WeaponAilment;
use App\Entity\Weapon\WeaponMaterial;
use App\Entity\Weapon\WeaponSlot;
use App\Enum\Ailment;
use App\Enum\Weapon\WeaponSlotType;
use App\Enum\Weapon\WeaponType;
use App\Model\Crawler\BaseCrawler;
use App\Synchronizer\Enum\WeaponSelector;
use App\Utils\CrawlerUtils;
use App\Utils\Utils;

class WeaponSynchronizer extends AbstractSynchronizer
{
    private const int BATCH_SIZE = 50;
    private const string ITEMS_LIST_PATH = 'data/weapons';

    public function synchronize(): void
    {
        $this->ping();
        $this->helper()->disableSQLLog();
        $this->helper()->cleanEntitiesData(
            WeaponMaterial::class,
            WeaponSlot::class,
            WeaponAilment::class,
            Weapon::class
        );

        foreach (WeaponType::cases() as $type) {
            $this->synchronizeType($type);
        }
    }

    private function synchronizeType(WeaponType $type): void
    {
        $this->logger()->debug(\sprintf('>>> Weapon : start sync "%s"', $type->label()));

        $url = \sprintf('%s?view=%s', $this->getListUrl(), $type->kiranicoView());
        $crawler = new BaseCrawler($url);

        $nodes = $crawler->findNodesBySelector(WeaponSelector::LIST_ITEM_TR->value);
        $crawler->clear();

        foreach ($nodes as $i => $node) {
            $this->synchronizeWeapon($node, $type);

            if (0 === $i % self::BATCH_SIZE) {
                $this->logger()->info(\sprintf('... ... ... %d / %d', $i, $nodes->count()));
                $this->flushAndClear();
            }
        }

        $this->logger()->info(\sprintf('... ... ... %d / %d', $nodes->count(), $nodes->count()));
        $this->logger()->info(Utils::getMemoryConsumption());
        $this->flushAndClear();
    }

    private function synchronizeWeapon(\DOMNode $node, WeaponType $type): void
    {
        $crawler = new BaseCrawler($node);

        $raritySmall = $crawler->findCurrentNodeBySelector(WeaponSelector::LIST_ITEM_RARITY_SMALL->value);
        if (null === $raritySmall) {
            return;
        }

        $rarity = Utils::cleanString($raritySmall->textContent);

        $anchor = $crawler->findCurrentNodeBySelector(WeaponSelector::LIST_ITEM_ANCHOR->value);
        $href = CrawlerUtils::findAttributeByName($anchor, 'href');
        if (null === $href) {
            return; // unprocessable
        }

        $crawler = new BaseCrawler($href);
        $weapon = new Weapon();
        $weapon->setType($type);
        $weapon->setRarity(\intval(\str_replace('Rare ', '', $rarity))); // only in index view

        $this->synchronizeName($weapon, $crawler);
        $this->synchronizeImages($weapon, $crawler);
        $this->synchronizeAttack($weapon, $crawler);
        $this->synchronizeSlots($weapon, $crawler);
        $this->synchronizeAilments($weapon, $crawler);

        if ($type->withSharpness()) {
            $this->synchronizeSharpness($weapon, $crawler);
            $this->synchronizeAffinity($weapon, $crawler);
        }

        $this->em()->persist($weapon);

        $crawler->clear();
    }

    private function synchronizeName(Weapon $weapon, BaseCrawler $crawler): void
    {
        $nameH1 = $crawler->findCurrentNodeBySelector(WeaponSelector::DETAIL_H1->value);
        $name = $nameH1 ? Utils::cleanString($nameH1->textContent) : null;
        if (null === $name) {
            return; // unprocessable
        }

        $weapon->setName($name);
    }

    private function synchronizeImages(Weapon $weapon, BaseCrawler $crawler): void
    {
        $imagesUrlsImg = $crawler->findNodesBySelector(WeaponSelector::DETAIL_IMG->value);
        foreach ($imagesUrlsImg as $image) {
            $src = CrawlerUtils::findAttributeByName($image, 'src');
            if (null === $src) {
                continue;
            }

            $weapon->addImageUrl($src);
        }
    }

    private function synchronizeAttack(Weapon $weapon, BaseCrawler $crawler): void
    {
        $attackDiv = $crawler->findCurrentNodeBySelector(WeaponSelector::DETAIL_ATTACK_DIV->value);
        $attack = $attackDiv ? Utils::cleanString($attackDiv->textContent) : null;
        $weapon->setAttack(\intval($attack));
    }

    private function synchronizeSlots(Weapon $weapon, BaseCrawler $crawler): void
    {
        $slotsSpans = $crawler->findNodesBySelector(WeaponSelector::DETAIL_SLOTS_SPAN->value);

        /* @var \DOMNode $ailmentSpan */
        foreach ($slotsSpans as $slotSpan) {
            $type = WeaponSlotType::tryFromKiranicoLabel($slotSpan->textContent);

            /** @var \DOMNode $child */
            foreach ($slotSpan->childNodes as $child) {
                if (!CrawlerUtils::is($child, 'img')) {
                    continue;
                }

                $src = CrawlerUtils::findAttributeByName($child, 'src');
                $quantity = $src ? $this->helper()->getWeaponSlotQuantityFromImageSrc($src) : null;
                if (null === $quantity) {
                    continue; // unprocessable
                }

                $slot = new WeaponSlot();
                $slot->setType($type);
                $slot->setQuantity($quantity);

                $weapon->addSlot($slot);
            }
        }
    }

    private function synchronizeAilments(Weapon $weapon, BaseCrawler $crawler): void
    {
        $ailmentsSpan = $crawler->findNodesBySelector(WeaponSelector::DETAIL_AILMENTS_SPAN->value);
        /** @var \DOMNode $ailmentSpan */
        foreach ($ailmentsSpan as $ailmentSpan) {
            $ailmentValueType = CrawlerUtils::findAttributeByName($ailmentSpan, 'data-value');
            $ailment = Ailment::tryFromValue(\intval($ailmentValueType));

            $valueSpan = CrawlerUtils::findFirstChildOfType($ailmentSpan, 'span');

            if (null === $ailmentValueType || null === $valueSpan || null === $ailment) {
                continue; // unprocessable
            }

            $weaponAilment = new WeaponAilment();
            $weaponAilment->setAilment($ailment);
            $weaponAilment->setValue(\intval(Utils::cleanString($valueSpan->textContent)));

            $weapon->addAilment($weaponAilment);
        }
    }

    private function synchronizeSharpness(Weapon $weapon, BaseCrawler $crawler): void
    {
        $sharpness = $crawler->findCurrentNodeBySelector(WeaponSelector::DETAIL_SHARPNESS_SMALL->value);
        if (null === $sharpness) {
            return; // unprocessable
        }

        $html = $sharpness->ownerDocument?->saveHTML($sharpness);
        if (empty($html)) {
            return; // unprocessable
        }

        $weapon->setSharpness(Utils::cleanString($html));
    }

    private function synchronizeAffinity(Weapon $weapon, BaseCrawler $crawler): void
    {
        $affinityDivs = $crawler->findNodesBySelector(WeaponSelector::DETAIL_AFFINITY_DEFENSE_BONUS_DIV->value);
        if (0 === $affinityDivs->count()) {
            return; // unprocessable
        }

        /** @var \DOMNode $affinityDiv
         */
        foreach ($affinityDivs as $affinityDiv) {
            $firstChildTextContent = $affinityDiv->firstChild?->textContent ?? '';
            if ('Affinity' !== Utils::cleanString($firstChildTextContent)) {
                continue;
            }

            $valueSpan = CrawlerUtils::findFirstChildOfType($affinityDiv, 'span');
            $value = $valueSpan ? \intval(Utils::cleanString($valueSpan->textContent)) : null;
            if (null === $value) {
                continue;
            }

            if (CrawlerUtils::hasClass($valueSpan, 'text-green-600')) {
                $weapon->setAffinity($value);
            } else {
                $weapon->setAffinity(-1 * abs($value));
            }
        }
    }

    private function getListUrl(): string
    {
        return \sprintf('%s/%s', $this->getKiranicoUrl(), self::ITEMS_LIST_PATH);
    }

    public static function getDefaultPriority(): int
    {
        return 70;
    }
}
