<?php

namespace App\Tests\Api;

use App\Entity\Quest\Quest;
use App\Factory\ItemFactory;
use App\Factory\Monster\MonsterFactory;
use App\Factory\Quest\QuestFactory;
use App\Factory\Quest\QuestMonsterFactory;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;

class QuestTest extends BaseJsonApiTestCase
{
    protected function setUp(): void
    {
        MonsterFactory::createMany(5); // used for QuestMonster
        ItemFactory::createMany(5); // used for QuestItem
    }

    #[Test]
    public function getQuests(): void
    {
        QuestFactory::createMany(self::ITEMS_PER_PAGE + 1);
        $this->client?->request('GET', '/api/quests');

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Quest/get_quests');
    }

    #[Test]
    public function getQuest(): void
    {
        /** @var Quest $quest */
        $quest = QuestFactory::createOne()->object();
        $this->client?->request('GET', \sprintf('/api/quests/%s', $quest->getId()));

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Quest/get_quest');
    }

    #[Test]
    public function getMonsters(): void
    {
        /** @var Quest $quest */
        $quest = QuestFactory::createOne()->object();
        $endpoint = \sprintf('/api/quests/%s/monsters', $quest->getId());
        $this->client?->request('GET', $endpoint);

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Quest/get_quest_monsters');
    }

    #[Test]
    public function getMonster(): void
    {
        /** @var Quest $quest */
        $quest = QuestFactory::createOne()->object();
        if (null === $monster = $quest->getMonsters()->first() ?: null) {
            $monster = QuestMonsterFactory::createOne(['quest' => $quest]);
        }

        $endpoint = \sprintf('/api/quests/%s/monsters/%s', $quest->getId(), $monster->getId());
        $this->client?->request('GET', $endpoint);

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Quest/get_quest_monster');
    }

    #[Test]
    public function getMonsterAttributes(): void
    {
        /** @var Quest $quest */
        $quest = QuestFactory::createOne()->object();
        if (null === $monster = $quest->getMonsters()->first() ?: null) {
            $monster = QuestMonsterFactory::createOne(['quest' => $quest]);
        }

        $endpoint = \sprintf('/api/quests/%s/monsters/%s/attributes',
            $quest->getId(), $monster->getId());
        $this->client?->request('GET', $endpoint);

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Quest/get_quest_monster_attributes');
    }

    #[Test]
    public function getMonsterSizes(): void
    {
        /** @var Quest $quest */
        $quest = QuestFactory::createOne()->object();
        if (null === $monster = $quest->getMonsters()->first() ?: null) {
            $monster = QuestMonsterFactory::createOne(['quest' => $quest]);
        }

        $endpoint = \sprintf('/api/quests/%s/monsters/%s/sizes',
            $quest->getId(), $monster->getId());
        $this->client?->request('GET', $endpoint);

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Quest/get_quest_monster_sizes');
    }

    #[Test]
    public function getMonsterAreas(): void
    {
        /** @var Quest $quest */
        $quest = QuestFactory::createOne()->object();
        if (null === $monster = $quest->getMonsters()->first() ?: null) {
            $monster = QuestMonsterFactory::createOne(['quest' => $quest]);
        }

        $endpoint = \sprintf('/api/quests/%s/monsters/%s/areas',
            $quest->getId(), $monster->getId());
        $this->client?->request('GET', $endpoint);

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Quest/get_quest_monster_areas');
    }

    #[Test]
    public function getItems(): void
    {
        /** @var Quest $quest */
        $quest = QuestFactory::createOne()->object();
        $endpoint = \sprintf('/api/quests/%s/items', $quest->getId());
        $this->client?->request('GET', $endpoint);

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Quest/get_quest_items');
    }
}
