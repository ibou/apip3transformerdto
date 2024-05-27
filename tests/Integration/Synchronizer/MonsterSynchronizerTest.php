<?php

namespace App\Tests\Integration\Synchronizer;

use App\Model\Crawler\BaseCrawler;
use App\Synchronizer\Enum\MonsterSelector;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MonsterSynchronizerTest extends KernelTestCase
{
    public function testListSelectors(): void
    {
        $crawler = new BaseCrawler('https://mhrise.kiranico.com/data/monsters?view=lg'); // FIXME

        $nodes = $crawler->findNodesBySelector(MonsterSelector::LIST_DIV->value);
        $this->assertGreaterThan(1, $nodes->count());

        $nodes = $crawler->findNodesBySelector(MonsterSelector::LIST_A->value);
        $this->assertGreaterThan(1, $nodes->count());

        $nodes = $crawler->findNodesBySelector(MonsterSelector::LIST_IMG->value);
        $this->assertGreaterThan(1, $nodes->count());
    }

    public function testDetailSelector(): void
    {
        $crawler = new BaseCrawler('https://mhrise.kiranico.com/data/monsters/1301934382'); // FIXME

        $nodes = $crawler->findNodesBySelector(MonsterSelector::DETAIL_TITLE_H1->value);
        $this->assertEquals(1, $nodes->count());

        $this->assertEquals(1, $nodes->count());

        $nodes = $crawler->findNodesBySelector(MonsterSelector::DETAIL_TABLE_TBODY->value);
        $this->assertGreaterThanOrEqual(3, $nodes->count());
    }
}
