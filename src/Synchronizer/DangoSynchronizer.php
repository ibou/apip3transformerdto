<?php

namespace App\Synchronizer;

use App\Entity\Kitchen\Dango;
use App\Model\Crawler\BaseCrawler;
use App\Synchronizer\Enum\DangoSelector;
use App\Utils\CrawlerUtils;
use App\Utils\Utils;

class DangoSynchronizer extends AbstractSynchronizer
{
    public const string ITEMS_LIST_PATH = 'data/kitchen-skills';

    public function synchronize(): void
    {
        $this->ping();
        $this->helper()->disableSQLLog();
        $this->helper()->cleanEntityData(Dango::class);

        $crawler = new BaseCrawler(\sprintf('%s/%s', $this->kiranicoUrl(), self::ITEMS_LIST_PATH));
        $trs = $crawler->findNodesBySelector(DangoSelector::LIST_TR->value);

        $this->startProgressBar($trs->count(), 'Dango');
        foreach ($trs as $tr) {
            $this->synchronizeDango($tr);
            $this->advanceProgressBar();
        }

        $this->flushAndClear();
        $this->finishProgressBar();
    }

    private function synchronizeDango(\DOMNode $node): void
    {
        $dango = new Dango();

        /** @var \DOMNode $child */
        foreach (CrawlerUtils::filterNodes($node->childNodes, 'td') as $key => $child) {
            $value = Utils::cleanString($child->textContent);
            match ($key) {
                0 => $dango->setName($value),
                1 => $dango->setEffects(\explode('.', $value)),
                default => ''
            };
        }

        if ($this->isValid($dango)) {
            $this->em()->persist($dango);
        }
    }

    public static function getDefaultPriority(): int
    {
        return 100;
    }
}
