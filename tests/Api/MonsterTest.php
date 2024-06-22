<?php

namespace App\Tests\Api;

use App\Entity\Monster\Monster;
use App\Factory\Monster\MonsterBodyPartFactory;
use App\Factory\Monster\MonsterFactory;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;

class MonsterTest extends BaseJsonApiTestCase
{
    #[Test]
    public function getMonsters(): void
    {
        MonsterFactory::createMany(self::ITEMS_PER_PAGE + 1);
        $this->client?->request('GET', '/api/monsters');

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Monster/get_monsters');
    }

    #[Test]
    public function getMonster(): void
    {
        /** @var Monster $monster */
        $monster = MonsterFactory::createOne()->object();
        $this->client?->request('GET', \sprintf('/api/monsters/%s', $monster->getId()));

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Monster/get_monster');
    }

    #[Test]
    public function getBodyParts(): void
    {
        /** @var Monster $monster */
        $monster = MonsterFactory::createOne()->object();
        $endpoint = \sprintf('/api/monsters/%s/body_parts', $monster->getId());
        $this->client?->request('GET', $endpoint);

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Monster/get_monster_body_parts');
    }

    #[Test]
    public function getBodyPart(): void
    {
        /** @var Monster $monster */
        $monster = MonsterFactory::createOne()->object();
        if (null === $bodyPart = $monster->getBodyParts()->first() ?: null) {
            $bodyPart = MonsterBodyPartFactory::createOne(['monster' => $monster]);
        }

        $endpoint = \sprintf('/api/monsters/%s/body_parts/%s', $monster->getId(), $bodyPart->getId());
        $this->client?->request('GET', $endpoint);

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Monster/get_monster_body_part');
    }

    #[Test]
    public function getBodyPartWeaknesses(): void
    {
        /** @var Monster $monster */
        $monster = MonsterFactory::createOne()->object();
        $bodyPart = $monster->getBodyParts()->first() ?: null;
        if (null === $bodyPart) { // FIXME
            $this->assertTrue(false);

            return;
        }

        $endpoint = \sprintf('/api/monsters/%s/body_parts/%s/weaknesses', $monster->getId(), $bodyPart->getId());
        $this->client?->request('GET', $endpoint);

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Monster/get_monster_body_part_weaknesses');
    }

    #[Test]
    public function getItems(): void
    {
        /** @var Monster $monster */
        $monster = MonsterFactory::createOne()->object();
        $endpoint = \sprintf('/api/monsters/%s/items', $monster->getId());
        $this->client?->request('GET', $endpoint);

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Monster/get_monster_items');
    }
}
