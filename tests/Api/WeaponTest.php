<?php

namespace App\Tests\Api;

use App\Entity\Equipment\Weapon\Weapon;
use App\Factory\Equipment\Skill\SkillFactory;
use App\Factory\Equipment\Weapon\WeaponFactory;
use App\Factory\ItemFactory;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;

class WeaponTest extends BaseJsonApiTestCase
{
    protected function setUp(): void
    {
        ItemFactory::createMany(5); // used for WeaponMaterial
        SkillFactory::createMany(5); // used for $skills property
    }

    #[Test]
    public function getWeapons(): void
    {
        WeaponFactory::createMany(self::ITEMS_PER_PAGE + 1);
        $this->client?->request('GET', '/api/weapons');

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Weapon/get_weapons');
    }

    #[Test]
    public function getWeapon(): void
    {
        /** @var Weapon $weapon */
        $weapon = WeaponFactory::createOne()->object();
        $this->client?->request('GET', \sprintf('/api/weapons/%s', $weapon->getId()));

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Weapon/get_weapon');
    }

    #[Test]
    public function getSlots(): void
    {
        /** @var Weapon $weapon */
        $weapon = WeaponFactory::createOne()->object();
        $endpoint = \sprintf('/api/weapons/%s/slots', $weapon->getId());
        $this->client?->request('GET', $endpoint);

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Weapon/get_weapon_slots');
    }

    #[Test]
    public function getMaterials(): void
    {
        /** @var Weapon $weapon */
        $weapon = WeaponFactory::createOne()->object();
        $endpoint = \sprintf('/api/weapons/%s/materials', $weapon->getId());
        $this->client?->request('GET', $endpoint);

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Weapon/get_weapon_materials');
    }

    #[Test]
    public function getStatuses(): void
    {
        /** @var Weapon $weapon */
        $weapon = WeaponFactory::createOne()->object();
        $endpoint = \sprintf('/api/weapons/%s/statuses', $weapon->getId());
        $this->client?->request('GET', $endpoint);

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Weapon/get_weapon_statuses');
    }

    #[Test]
    public function getExtras(): void
    {
        /** @var Weapon $weapon */
        $weapon = WeaponFactory::createOne()->object();
        $endpoint = \sprintf('/api/weapons/%s/extras', $weapon->getId());
        $this->client?->request('GET', $endpoint);

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Weapon/get_weapon_extras');
    }
}
