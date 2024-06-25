<?php

namespace App\Synchronizer;

use App\Entity\Equipment\Weapon\Weapon;
use App\Entity\Equipment\Weapon\WeaponExtra;
use App\Entity\Equipment\Weapon\WeaponMaterial;
use App\Entity\Equipment\Weapon\WeaponSlot;
use App\Entity\Equipment\Weapon\WeaponStatus;
use App\Enum\Equipment\EquipmentMaterialType;
use App\Enum\Equipment\EquipmentSlotType;
use App\Enum\Equipment\Skill\SkillType;
use App\Enum\Equipment\Weapon\WeaponType;
use App\Enum\StatusEffect;
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
            WeaponExtra::class,
            WeaponMaterial::class,
            WeaponSlot::class,
            WeaponStatus::class,
            Weapon::class
        );

        foreach (WeaponType::cases() as $type) {
            $this->synchronizeType($type);
        }
    }

    private function synchronizeType(WeaponType $type): void
    {
        $this->logger()->info(\sprintf('>>> Weapon : start sync "%s"', $type->label()));

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
        if (null === $weapon->getName()) {
            $this->logger()->warning($href);

            return;
        }

        $this->synchronizeImages($weapon, $crawler);
        $this->synchronizeAttack($weapon, $crawler);
        $this->synchronizeMaterials($weapon, $crawler);
        $this->synchronizeSlots($weapon, $crawler);
        $this->synchronizeAilments($weapon, $crawler);

        if ($weapon->getType()?->withSharpness()) {
            $this->synchronizeSharpness($weapon, $crawler);
            $this->synchronizeAffinity($weapon, $crawler);
        }

        if ($weapon->is(WeaponType::HUNTING_HORN)) {
            $this->synchronizeHuntingHornStatuses($weapon, $crawler);
        }

        if ($weapon->is(WeaponType::GUNLANCE)) {
            $this->synchronizeGunlanceExtra($weapon, $crawler);
        }

        if ($weapon->is(WeaponType::SWITCH_AXE, WeaponType::CHARGE_BLADE, WeaponType::INSECT_GLAIVE)) {
            $this->synchronizeSwitchAxeChargeBladeInsectGlaiveExtra($weapon, $crawler);
        }

        if ($weapon->is(WeaponType::BOW)) {
            $this->synchronizeBowExtra($weapon, $crawler);
        }

        if ($weapon->is(WeaponType::LIGHT_BOWGUN, WeaponType::HEAVY_BOWGUN)) {
            $this->synchronizeBowgunExtra($weapon, $crawler);
        }

        $this->synchronizeSkills($weapon, $crawler);

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

    private function synchronizeMaterials(Weapon $weapon, BaseCrawler $crawler): void
    {
        $materialsDivs = $crawler->findNodesBySelector(WeaponSelector::DETAIL_MATERIALS_DIV->value);

        /* @var \DOMNode $ailmentSpan */
        foreach ($materialsDivs as $materialDiv) {
            $_crawler = new BaseCrawler($materialDiv);
            $type = Utils::cleanString($_crawler->findCurrentNodeBySelector('h2')?->textContent ?? '');
            $enum = EquipmentMaterialType::tryFromLabel($type);

            if (null === $enum) {
                continue;
            }

            $trs = $_crawler->findNodesBySelector('tr');
            foreach ($trs as $tr) {
                $this->synchronizeMaterial($weapon, $tr, $enum);
            }
        }
    }

    private function synchronizeMaterial(Weapon $weapon, \DOMNode $node, EquipmentMaterialType $type): void
    {
        $key = 0;
        $material = new WeaponMaterial();
        $material->setType($type);

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

                $material->setItem($item);
            }

            match ($key) {
                1 => $material->setQuantity(\intval(\str_replace('x', '', $value))),
                default => null,
            };

            ++$key;
        }

        $weapon->addMaterial($material);
    }

    private function synchronizeSlots(Weapon $weapon, BaseCrawler $crawler): void
    {
        $slotsSpans = $crawler->findNodesBySelector(WeaponSelector::DETAIL_SLOTS_SPAN->value);

        /* @var \DOMNode $ailmentSpan */
        foreach ($slotsSpans as $slotSpan) {
            $type = EquipmentSlotType::tryFromKiranicoLabel($slotSpan->textContent);

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
            $statusValueType = CrawlerUtils::findAttributeByName($ailmentSpan, 'data-value');
            $status = StatusEffect::tryFromValue(\intval($statusValueType));

            $valueSpan = CrawlerUtils::findFirstChildOfType($ailmentSpan, 'span');

            if (null === $statusValueType || null === $valueSpan || null === $status) {
                continue; // unprocessable
            }

            $weaponStatus = new WeaponStatus();
            $weaponStatus->setStatus($status);
            $weaponStatus->setValue(\intval(Utils::cleanString($valueSpan->textContent)));

            $weapon->addStatus($weaponStatus);
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
                $weapon->setAffinity(-1 * \abs($value));
            }
        }
    }

    private function synchronizeHuntingHornStatuses(Weapon $weapon, BaseCrawler $crawler): void
    {
        $statusSmall = $crawler->findCurrentNodeBySelector(WeaponSelector::DETAIL_HUNTING_HORN_STATUSES->value);
        if (null === $statusSmall) {
            return; // unprocessable
        }

        /** @var \DOMNode $childNode */
        foreach ($statusSmall->childNodes as $childNode) {
            if (!CrawlerUtils::is($childNode, 'div')) {
                continue;
            }

            $textContent = Utils::cleanString($childNode->textContent);
            $enum = StatusEffect::tryFromLabel($textContent);
            $status = new WeaponStatus();
            $status->setStatus($enum);

            $weapon->addStatus($status);
        }
    }

    private function synchronizeGunlanceExtra(Weapon $weapon, BaseCrawler $crawler): void
    {
        $lanceTypeDiv = $crawler->findCurrentNodeBySelector(WeaponSelector::DETAIL_GUNLANCE_TYPE_OR_BOW_COATINGS_DIV->value);
        if (null === $lanceTypeDiv) {
            return; // unprocessable
        }

        $extra = new WeaponExtra();
        $extra->setName(Utils::cleanString($lanceTypeDiv->textContent));
        $weapon->addExtra($extra);
    }

    private function synchronizeSwitchAxeChargeBladeInsectGlaiveExtra(Weapon $weapon, BaseCrawler $crawler): void
    {
        $switchAxeExtraDiv = $crawler->findCurrentNodeBySelector(
            WeaponSelector::DETAIL_SWITCH_AXE_OR_CHARGE_BLADE_INSECT_GLAIVE_EXTRA_DIV->value
        );
        if (null === $switchAxeExtraDiv) {
            return; // unprocessable
        }

        $extra = new WeaponExtra();
        $extra->setName(Utils::cleanString($switchAxeExtraDiv->textContent));
        $weapon->addExtra($extra);
    }

    private function synchronizeBowExtra(Weapon $weapon, BaseCrawler $crawler): void
    {
        $coatingsDivs = $crawler->findNodesBySelector(WeaponSelector::DETAIL_GUNLANCE_TYPE_OR_BOW_COATINGS_DIV->value);
        foreach ($coatingsDivs as $coatingDiv) {
            $extra = new WeaponExtra();
            $extra->setName(Utils::cleanString($coatingDiv->textContent));
            $extra->setActive(!CrawlerUtils::hasClass($coatingDiv, 'text-gray-400'));
            $weapon->addExtra($extra);
        }

        $shotsDivs = $crawler->findNodesBySelector(WeaponSelector::DETAIL_BOW_SHOTS_DIV->value);
        foreach ($shotsDivs as $shotDiv) {
            $extra = new WeaponExtra();
            $extra->setName(Utils::cleanString($shotDiv->textContent));
            $extra->setActive(!CrawlerUtils::hasClass($shotDiv, 'text-gray-400'));
            $weapon->addExtra($extra);
        }
    }

    private function synchronizeBowgunExtra(Weapon $weapon, BaseCrawler $crawler): void
    {
        $bowgunsDivs = $crawler->findNodesBySelector(WeaponSelector::DETAIL_BOWGUN_EXTRA_DIV->value);
        foreach ($bowgunsDivs as $bowgunDiv) {
            $extra = new WeaponExtra();
            $extra->setName(Utils::replaceMultipleSpacesByOne(Utils::cleanString($bowgunDiv->textContent)));
            $weapon->addExtra($extra);
        }

        $bowgunsAmmoTrs = $crawler->findNodesBySelector(WeaponSelector::DETAIL_BOWGUN_AMMO_EXTRA_TR->value);
        /** @var \DOMNode $bowgunAmmoTr */
        foreach ($bowgunsAmmoTrs as $bowgunAmmoTr) {
            $name = null;
            $key = 0;

            /** @var \DOMNode $child */
            foreach ($bowgunAmmoTr->childNodes as $child) {
                if (!CrawlerUtils::is($child, 'td')) {
                    continue;
                }

                $value = Utils::cleanString($child->textContent);

                if (0 === $key) {
                    $name = $value;
                    ++$key;

                    continue;
                }

                $extra = new WeaponExtra();
                $extra->setName(\sprintf('%s %s', $name, $key));
                $extra->setValue(\intval($value));
                $extra->setActive(!CrawlerUtils::hasClass($child, 'text-gray-400'));

                $weapon->addExtra($extra);

                ++$key;
            }
        }
    }

    public function synchronizeSkills(Weapon $weapon, BaseCrawler $crawler): void
    {
        $skillsDivs = $crawler->findNodesBySelector(WeaponSelector::DETAIL_SKILLS_DIV->value);

        /** @var \DOMNode $skillDiv */
        foreach ($skillsDivs as $skillDiv) {
            $nameAndLevel = Utils::splitStringInTwoByLastWhitespace(Utils::cleanString($skillDiv->textContent));
            if (!isset($nameAndLevel[0]) || !isset($nameAndLevel[1])) {
                continue;
            }

            $level = $this->cache()
                ->findSkillLevel($nameAndLevel[0], SkillType::RAMPAGE, Utils::romanToNumber($nameAndLevel[1]));
            if (null === $level) {
                continue;
            }

            $weapon->addSkill($level);
        }
    }

    private function getListUrl(): string
    {
        return \sprintf('%s/%s', $this->getKiranicoUrl(), self::ITEMS_LIST_PATH);
    }

    public static function getDefaultPriority(): int
    {
        return 60;
    }
}
