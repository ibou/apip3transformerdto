<?php

namespace App\Synchronizer;

use App\Entity\Item;
use App\Enum\Item\ItemType;
use App\Model\Crawler\BaseCrawler;
use App\Synchronizer\Enum\ItemSelector;
use App\Utils\CrawlerUtils;
use App\Utils\Utils;

class ItemSynchronizer extends AbstractSynchronizer
{
    public const string ITEMS_LIST_PATH = 'data/items';
    private const int BATCH_SIZE = 250;

    public function synchronize(): void
    {
        $this->ping();
        $this->helper()->disableSQLLog();
        $this->helper()->cleanEntityData(Item::class);

        foreach (ItemType::cases() as $type) {
            $this->synchronizeType($type);
        }
    }

    private function synchronizeType(ItemType $type): void
    {
        $crawler = new BaseCrawler(\sprintf('%s?view=%s', $this->getListUrl(), $type->value));
        $nodes = $crawler->findNodesBySelector(ItemSelector::LIST_DIV->value);

        $this->startProgressBar($nodes->count(), \sprintf('Item > "%s"', $type->label()));
        foreach ($nodes as $i => $node) {
            $this->synchronizeItem($node, $type);
            $this->advanceProgressBar();

            if (0 !== $i && 0 === $i % self::BATCH_SIZE) {
                $this->flushAndClear();
            }
        }

        $this->flushAndClear();
        $this->finishProgressBar();
    }

    private function synchronizeItem(\DOMNode $node, ItemType $type): void
    {
        $listCrawler = new BaseCrawler($node);
        $a = $listCrawler->findCurrentNodeBySelector(ItemSelector::LIST_ANCHOR->value);
        if (null === $a) {
            return; // unprocessable
        }

        $href = CrawlerUtils::findAttributeByName($a, 'href');
        if (null === $href) {
            return; // unprocessable
        }

        $crawler = new BaseCrawler($href);

        $item = new Item();
        $item->setType($type);

        $this->synchronizeName($item, $crawler);
        $this->synchronizeDescription($item, $crawler);
        $this->synchronizeImagesUrls($item, $listCrawler);

        if ($this->isValid($item)) {
            $this->em()->persist($item);
        }
    }

    private function synchronizeName(Item $item, BaseCrawler $crawler): void
    {
        $nameH1 = $crawler->findCurrentNodeBySelector(ItemSelector::DETAIL_NAME_H1->value);
        if (null === $nameH1) {
            return; // unprocessable
        }

        $name = Utils::cleanString($nameH1->textContent);
        $item->setName($name);
    }

    private function synchronizeDescription(Item $item, BaseCrawler $crawler): void
    {
        $descriptionP = $crawler->findCurrentNodeBySelector(ItemSelector::DETAIL_DESCRIPTION_P->value);
        if (null === $descriptionP) {
            return; // unprocessable
        }

        $description = Utils::cleanString($descriptionP->textContent);
        $item->setDescription($description);
    }

    private function synchronizeImagesUrls(Item $item, BaseCrawler $crawler): void
    {
        $node = $crawler->findCurrentNodeBySelector(ItemSelector::LIST_IMG->value);
        $src = CrawlerUtils::findAttributeByName($node, 'src');
        if (null === $src) {
            return; // unprocessable
        }

        $item->setImageUrl($src);
    }

    private function getListUrl(): string
    {
        return \sprintf('%s/%s', $this->kiranicoUrl(), self::ITEMS_LIST_PATH);
    }

    public static function getDefaultPriority(): int
    {
        return 100;
    }
}
