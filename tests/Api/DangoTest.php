<?php

namespace App\Tests\Api;

use App\Entity\Kitchen\Dango;
use App\Factory\Kitchen\DangoFactory;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;

class DangoTest extends BaseJsonApiTestCase
{
    #[Test]
    public function getDangos(): void
    {
        DangoFactory::createMany(self::ITEMS_PER_PAGE + 1);
        $this->client?->request('GET', '/api/kitchen/dangos');

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Dango/get_dangos');
    }

    #[Test]
    public function getDango(): void
    {
        /** @var Dango $dango */
        $dango = DangoFactory::createOne()->object();
        $this->client?->request('GET', \sprintf('/api/kitchen/dangos/%s', $dango->getId()));

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Dango/get_dango');
    }
}
