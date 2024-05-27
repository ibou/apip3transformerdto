<?php

namespace App\Tests\Integration\Synchronizer;

use App\Model\Crawler\BaseCrawler;
use App\Synchronizer\Enum\ItemSelector;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ItemSynchronizerTest extends KernelTestCase
{
    public function testListSelectors(): void
    {
        $crawler = new BaseCrawler('https://mhrise.kiranico.com/data/items?view=consume'); // FIXME

        $nodes = $crawler->findNodesBySelector(ItemSelector::LIST_DIV->value);
        $this->assertGreaterThan(1, $nodes->count());

        $nodes = $crawler->findNodesBySelector(ItemSelector::LIST_A->value);
        $this->assertGreaterThan(1, $nodes->count());

        $nodes = $crawler->findNodesBySelector(ItemSelector::LIST_IMG->value);
        $this->assertGreaterThan(1, $nodes->count());
    }

    public function testDetailSelector(): void
    {
        $crawler = new BaseCrawler('https://mhrise.kiranico.com/data/items/470023020'); // FIXME

        $nodes = $crawler->findNodesBySelector(ItemSelector::DETAIL_TITLE_H1->value);
        $this->assertEquals(1, $nodes->count());

        $nodes = $crawler->findNodesBySelector(ItemSelector::DETAIL_DESCRIPTION_P->value);
        $this->assertEquals(1, $nodes->count());
    }
}
