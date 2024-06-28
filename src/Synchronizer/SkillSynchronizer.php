<?php

namespace App\Synchronizer;

use App\Entity\Equipment\Skill\Skill;
use App\Entity\Equipment\Skill\SkillLevel;
use App\Enum\Equipment\Skill\SkillType;
use App\Model\Crawler\BaseCrawler;
use App\Synchronizer\Enum\SkillSelector;
use App\Utils\CrawlerUtils;
use App\Utils\Utils;

class SkillSynchronizer extends AbstractSynchronizer
{
    private const int BATCH_SIZE = 1;
    private const string SKILLS_LIST_PATH = 'data/skills';
    private const string RAMPAGE_SKILLS_LIST_PATH = 'data/rampage-skills';

    public function synchronize(): void
    {
        $this->ping();
        $this->helper()->disableSQLLog();
        $this->helper()->cleanEntitiesData(SkillLevel::class, Skill::class);

        $this->synchronizeSkills();
        $this->synchronizeRampageSkills();
    }

    private function synchronizeSkills(): void
    {
        $crawler = new BaseCrawler($this->getSkillsUrl());
        $nodes = $crawler->findNodesBySelector(SkillSelector::LIST_BASIC_SKILLS_ANCHOR->value);

        $this->startProgressBar($nodes->count(), \sprintf('Skill > "%s"', SkillType::BASIC->label()));
        foreach ($nodes as $i => $node) {
            $this->synchronizeSkill($node);
            $this->advanceProgressBar();

            if (0 === $i % self::BATCH_SIZE) {
                $this->flushAndClear();
            }
        }

        $this->flushAndClear();
        $this->finishProgressBar();
    }

    private function synchronizeSkill(\DOMNode $node): void
    {
        $href = CrawlerUtils::findAttributeByName($node, 'href');
        if (null === $href) {
            return; // unprocessable
        }

        $crawler = new BaseCrawler($href);

        $skill = new Skill();
        $skill->setType(SkillType::BASIC);

        $this->synchronizeName($skill, $crawler);
        $this->synchronizeDescription($skill, $crawler);
        $this->synchronizeSkillLevels($skill, $crawler);

        if (0 === $this->validator()->validate($skill)->count()) {
            $this->em()->persist($skill);
        }
    }

    private function synchronizeName(Skill $skill, BaseCrawler $crawler): void
    {
        $nameH1 = $crawler->findCurrentNodeBySelector(SkillSelector::DETAIL_NAME_H1->value);
        if (null === $nameH1) {
            return; // unprocessable;
        }

        $name = Utils::cleanString($nameH1->textContent);
        $skill->setName($name);
    }

    private function synchronizeDescription(Skill $skill, BaseCrawler $crawler): void
    {
        $descriptionP = $crawler->findCurrentNodeBySelector(SkillSelector::DETAIL_DESCRIPTION_P->value);
        if (null === $descriptionP) {
            return; // unprocessable
        }

        $description = Utils::cleanString($descriptionP->textContent);
        $skill->setDescription($description);
    }

    private function synchronizeSkillLevels(Skill $skill, BaseCrawler $crawler): void
    {
        $levelsTrs = $crawler->findNodesBySelector(SkillSelector::DETAIL_BASIC_LEVELS_TR->value);
        foreach ($levelsTrs as $levelTr) {
            $skillLevel = new SkillLevel();
            $key = 0;

            /** @var \DOMNode $child */
            foreach ($levelTr->childNodes as $child) {
                if (!CrawlerUtils::is($child, 'td')) {
                    continue;
                }

                $value = Utils::cleanString($child->textContent);

                match ($key) {
                    0 => $skillLevel->setLevel(\intval(\str_replace('Lv ', '', $value))),
                    1 => $skillLevel->setEffect($value),
                    default => ''
                };

                ++$key;
            }

            $skill->addLevel($skillLevel);
        }
    }

    private function synchronizeRampageSkills(): void
    {
        $crawler = new BaseCrawler($this->getRampageSkillsUrl());
        $nodes = $crawler->findNodesBySelector(SkillSelector::LIST_BASIC_SKILLS_ANCHOR->value);

        $this->startProgressBar($nodes->count(), \sprintf('Skill > "%s"', SkillType::RAMPAGE->label()));
        foreach ($nodes as $i => $node) {
            $this->synchronizeRampageSkill($node);
            $this->advanceProgressBar();

            if (0 === $i % self::BATCH_SIZE) {
                $this->flushAndClear();
            }
        }

        $this->flushAndClear();
        $this->finishProgressBar();
    }

    private function synchronizeRampageSkill(\DOMNode $node): void
    {
        $href = CrawlerUtils::findAttributeByName($node, 'href');
        if (null === $href) {
            return; // unprocessable
        }

        $crawler = new BaseCrawler($href);

        $nameH1 = $crawler->findCurrentNodeBySelector(SkillSelector::DETAIL_NAME_H1->value);
        if (null === $nameH1) {
            return; // unprocessable
        }

        $nameAndLevel = Utils::splitStringInTwoByLastWhitespace(Utils::cleanString($nameH1->textContent));
        if (!isset($nameAndLevel[0]) || !isset($nameAndLevel[1])) {
            return; // unprocessable
        }

        $descriptionP = $crawler->findCurrentNodeBySelector(SkillSelector::DETAIL_DESCRIPTION_P->value);

        if (null === $skill = $this->cache()->findSkill($nameAndLevel[0], SkillType::RAMPAGE)) {
            $skill = new Skill();
            $skill->setType(SkillType::RAMPAGE);
        }

        $skill->setName($nameAndLevel[0]);

        $skillLevel = new SkillLevel();
        $skillLevel->setLevel(Utils::romanToNumber($nameAndLevel[1]));
        if (null !== $descriptionP) {
            $skillLevel->setEffect($descriptionP->textContent);
        }
        $skill->addLevel($skillLevel);

        if (0 === $this->validator()->validate($skill)->count()) {
            $this->em()->persist($skill);
        }
    }

    private function getSkillsUrl(): string
    {
        return \sprintf('%s/%s', $this->kiranicoUrl(), self::SKILLS_LIST_PATH);
    }

    private function getRampageSkillsUrl(): string
    {
        return \sprintf('%s/%s', $this->kiranicoUrl(), self::RAMPAGE_SKILLS_LIST_PATH);
    }

    public static function getDefaultPriority(): int
    {
        return 70;
    }
}
