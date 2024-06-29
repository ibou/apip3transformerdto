<?php

namespace App\Synchronizer;

use App\Entity\Equipment\Companion\CompanionArmor;
use App\Entity\Equipment\Companion\CompanionArmorResistance;
use App\Entity\Equipment\Companion\CompanionWeapon;
use App\Entity\Equipment\Companion\CompanionWeaponStatus;
use App\Enum\Companion\CompanionType;
use App\Enum\Companion\CompanionWeaponType;
use App\Enum\StatusEffect;
use App\Model\Crawler\BaseCrawler;
use App\Synchronizer\Enum\CompanionEquipmentSelector;
use App\Utils\CrawlerUtils;
use App\Utils\Utils;

class CompanionEquipmentSynchronizer extends AbstractSynchronizer
{
    public function synchronize(): void
    {
        $this->ping();
        $this->helper()->disableSQLLog();
        $this->helper()->cleanEntitiesData(
            CompanionArmorResistance::class,
            CompanionArmor::class,
            CompanionWeaponStatus::class,
            CompanionWeapon::class
        );

        foreach (CompanionType::cases() as $type) {
            $this->synchronizeType($type);
        }
    }

    public function synchronizeType(CompanionType $type): void
    {
        $this->synchronizeWeapons($type);
        $this->synchronizeArmors($type);
    }

    private function synchronizeWeapons(CompanionType $type): void
    {
        $url = \sprintf('%s/data/%s?view=weapons', $this->kiranicoUrl(), $type->plural());
        $crawler = new BaseCrawler($url);

        $trs = $crawler->findNodesBySelector(CompanionEquipmentSelector::LIST_TR->value);
        $this->startProgressBar($trs->count(), \sprintf('%s > Weapons', $type->label()));

        /** @var \DOMNode $tr */
        foreach ($trs as $tr) {
            $weapon = new CompanionWeapon();
            $weapon->setCompanion($type);
            $key = 0;

            /** @var \DOMNode $childNode */
            foreach ($tr->childNodes as $childNode) {
                if (!CrawlerUtils::is($childNode, 'td')) {
                    continue;
                }

                $value = Utils::cleanString($childNode->textContent);
                if (0 === $key) {
                    $weapon->setName($value);
                } elseif (1 === $key) {
                    $weapon->setType(CompanionWeaponType::fromLabel($value));
                } elseif (2 === $key) {
                    [$melee, $ranged] = \explode(' / ', $value);
                    $weapon->setMeleeAttack(\intval($melee));
                    $weapon->setRangedAttack(\intval($ranged));
                } elseif (3 === $key && !empty($value)) {
                    $this->synchronizeWeaponStatuses($weapon, new BaseCrawler($childNode));
                } elseif (4 === $key && !empty($value)) {
                    $this->synchronizeWeaponAffinities($weapon, new BaseCrawler($childNode));
                } elseif (5 === $key && !empty($value)) {
                    $weapon->setDefenseBonus(\intval($value));
                }

                ++$key;
            }

            $this->em()->persist($weapon);
            $this->advanceProgressBar();
        }

        $this->flushAndClear();
        $this->finishProgressBar();
    }

    private function synchronizeWeaponStatuses(CompanionWeapon $weapon, BaseCrawler $crawler): void
    {
        $statusesSpan = $crawler->findNodesBySelector('span');

        /** @var \DOMNode $statusSpan */
        foreach ($statusesSpan as $statusSpan) {
            if (!CrawlerUtils::hasClass($statusSpan, 'inline-flex')) { // FIXME
                continue;
            }

            $status = new CompanionWeaponStatus();

            $statusValueType = CrawlerUtils::findAttributeByName($statusSpan, 'data-value');
            $status->setStatus(StatusEffect::tryFromValue(\intval($statusValueType)));

            /** @var \DOMNode $child */
            foreach ($statusSpan->childNodes as $child) {
                if (CrawlerUtils::is($child, 'span')) {
                    $status->setValue(\intval(Utils::cleanString($child->textContent)));
                }
            }

            $weapon->addStatus($status);
        }
    }

    private function synchronizeWeaponAffinities(CompanionWeapon $weapon, BaseCrawler $crawler): void
    {
        $affinitiesSpan = $crawler->findNodesBySelector('span');

        /** @var \DOMNode $affinitiySpan */
        foreach ($affinitiesSpan as $key => $affinitiySpan) {
            $value = Utils::cleanString($affinitiySpan->textContent);

            match ($key) {
                0 => $weapon->setMeleeAffinity(\intval($value)),
                1 => $weapon->setRangedAffinity(\intval($value)),
                default => ''
            };
        }
    }

    private function synchronizeArmors(CompanionType $type): void
    {
        $url = \sprintf('%s/data/%s?view=armor', $this->kiranicoUrl(), $type->plural());
        $crawler = new BaseCrawler($url);

        $trs = $crawler->findNodesBySelector(CompanionEquipmentSelector::LIST_TR->value);
        $this->startProgressBar($trs->count(), \sprintf('%s > Armors', $type->label()));

        /** @var \DOMNode $tr */
        foreach ($trs as $tr) {
            $armor = new CompanionArmor();
            $armor->setCompanion($type);
            $key = 0;

            /** @var \DOMNode $childNode */
            foreach ($tr->childNodes as $childNode) {
                if (!CrawlerUtils::is($childNode, 'td')) {
                    continue;
                }

                $value = Utils::cleanString($childNode->textContent);
                if (0 === $key) {
                    $armor->setName($value);
                } elseif (1 === $key) {
                    $armor->setDefense(\intval($value));
                } elseif (2 === $key) {
                    $this->synchronizeArmorResistances($armor, new BaseCrawler($childNode));
                }

                ++$key;
            }

            $this->em()->persist($armor);
            $this->advanceProgressBar();
        }

        $this->flushAndClear();
        $this->finishProgressBar();
    }

    private function synchronizeArmorResistances(CompanionArmor $armor, BaseCrawler $crawler): void
    {
        $elementsSpan = $crawler->findNodesBySelector('span');

        /** @var \DOMNode $elementSpan */
        foreach ($elementsSpan as $elementSpan) {
            if (!CrawlerUtils::hasClass($elementSpan, 'inline-flex')) { // FIXME
                continue;
            }

            $resistance = new CompanionArmorResistance();

            $elementValueType = CrawlerUtils::findAttributeByName($elementSpan, 'data-value');
            $resistance->setElement(StatusEffect::tryFromValue(\intval($elementValueType)));

            /** @var \DOMNode $child */
            foreach ($elementSpan->childNodes as $child) {
                if (CrawlerUtils::is($child, 'span')) {
                    $resistance->setValue(\intval(Utils::cleanString($child->textContent)));
                }
            }

            $armor->addResistance($resistance);
        }
    }

    public static function getDefaultPriority(): int
    {
        return 40;
    }
}
