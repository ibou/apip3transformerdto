<?php

namespace App\Tests\Api;

use App\Entity\Item;
use App\Factory\ItemFactory;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;

class ItemTest extends BaseJsonApiTestCase
{
    #[Test]
    public function collection(): void
    {
        ItemFactory::createMany(self::ITEMS_PER_PAGE + 1);
        $this->client?->request('GET', '/api/items');

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'get_items');
    }

    #[Test]
    public function item(): void
    {
        /** @var Item $item */
        $item = ItemFactory::createOne()->object();
        $this->client?->request('GET', \sprintf('/api/items/%s', $item->getId()));

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'get_item');
    }
}
