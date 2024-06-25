<?php

namespace App\Tests\Api;

use App\Entity\Equipment\Armor\Armor;
use App\Factory\Equipment\Armor\ArmorFactory;
use App\Factory\Equipment\Skill\SkillFactory;
use App\Factory\ItemFactory;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;

class ArmorTest extends BaseJsonApiTestCase
{
    protected function setUp(): void
    {
        ItemFactory::createMany(5); // used for ArmorMaterial
        SkillFactory::createMany(5); // used for $skills property
    }

    #[Test]
    public function getArmors(): void
    {
        ArmorFactory::createMany(self::ITEMS_PER_PAGE + 1);
        $this->client?->request('GET', '/api/armors');

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Armor/get_armors');
    }

    #[Test]
    public function getArmor(): void
    {
        /** @var Armor $armor */
        $armor = ArmorFactory::createOne()->object();
        $this->client?->request('GET', \sprintf('/api/armors/%s', $armor->getId()));

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Armor/get_armor');
    }

    #[Test]
    public function getSlots(): void
    {
        /** @var Armor $armor */
        $armor = ArmorFactory::createOne()->object();
        $endpoint = \sprintf('/api/armors/%s/slots', $armor->getId());
        $this->client?->request('GET', $endpoint);

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Armor/get_armor_slots');
    }

    #[Test]
    public function getMaterials(): void
    {
        /** @var Armor $armor */
        $armor = ArmorFactory::createOne()->object();
        $endpoint = \sprintf('/api/armors/%s/materials', $armor->getId());
        $this->client?->request('GET', $endpoint);

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Armor/get_armor_materials');
    }

    #[Test]
    public function getResistances(): void
    {
        /** @var Armor $armor */
        $armor = ArmorFactory::createOne()->object();
        $endpoint = \sprintf('/api/armors/%s/resistances', $armor->getId());
        $this->client?->request('GET', $endpoint);

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Armor/get_armor_resistances');
    }
}
