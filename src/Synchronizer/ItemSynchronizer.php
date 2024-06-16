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
    private const int BATCH_SIZE = 50;
    private const string ITEMS_LIST_PATH = 'data/items';

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
        $this->logger()->debug(\sprintf('>>> Item : start sync "%s"', $type->label()));

        $url = \sprintf('%s?view=%s', $this->getListUrl(), $type->value);
        $crawler = new BaseCrawler($url);

        $nodes = $crawler->findNodesBySelector(ItemSelector::LIST_DIV->value);
        $crawler->clear();

        foreach ($nodes as $i => $node) {
            $this->synchronizeItem($node, $type);

            if (0 === $i % self::BATCH_SIZE) {
                $this->logger()->info(\sprintf('... ... ... %d / %d', $i, $nodes->count()));
                $this->flushAndClear();
            }
        }

        $this->logger()->info(\sprintf('... ... ... %d / %d', $nodes->count(), $nodes->count()));
        $this->logger()->info(Utils::getMemoryConsumption());
        $this->flushAndClear();
    }

    private function synchronizeItem(\DOMNode $node, ItemType $type): void
    {
        $crawler = new BaseCrawler($node);
        $a = $crawler->findCurrentNodeBySelector(ItemSelector::LIST_A->value);
        if (null === $a) {
            return; // unprocessable
        }

        $href = CrawlerUtils::findAttributeByName($a, 'href');
        if (null === $href) {
            return; // unprocessable
        }

        $iconImg = $crawler->findCurrentNodeBySelector(ItemSelector::LIST_IMG->value);
        $crawler->clear();

        $crawler = new BaseCrawler($href);
        $titleH1 = $crawler->findCurrentNodeBySelector(ItemSelector::DETAIL_TITLE_H1->value);
        $descriptionP = $crawler->findCurrentNodeBySelector(ItemSelector::DETAIL_DESCRIPTION_P->value);

        if (null === $titleH1?->textContent) {
            return; // unprocessable
        }

        $item = new Item();
        $item->setType($type);
        $item->setName(Utils::cleanString($titleH1->textContent));
        $item->setDescription($descriptionP ? Utils::cleanString($descriptionP->textContent) : null);
        $item->setImageUrl($iconImg ? CrawlerUtils::findAttributeByName($iconImg, 'src') : null);

        $this->em()->persist($item);
    }

    private function getListUrl(): string
    {
        return \sprintf('%s/%s', $this->getKiranicoUrl(), self::ITEMS_LIST_PATH);
    }

    public static function getDefaultPriority(): int
    {
        return 100;
    }
}
