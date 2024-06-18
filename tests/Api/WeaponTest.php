<?php

namespace App\Tests\Api;

use App\Entity\Weapon\Weapon;
use App\Factory\ItemFactory;
use App\Factory\Weapon\WeaponExtraFactory;
use App\Factory\Weapon\WeaponFactory;
use App\Factory\Weapon\WeaponMaterialFactory;
use App\Factory\Weapon\WeaponSlotFactory;
use App\Factory\Weapon\WeaponStatusFactory;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;

class WeaponTest extends BaseJsonApiTestCase
{
    protected function setUp(): void
    {
        ItemFactory::createMany(5); // used for WeaponMaterial
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
    public function getSlot(): void
    {
        /** @var Weapon $weapon */
        $weapon = WeaponFactory::createOne()->object();
        if (null === $slot = $weapon->getSlots()->first() ?: null) {
            $slot = WeaponSlotFactory::createOne(['weapon' => $weapon]);
        }

        $endpoint = \sprintf('/api/weapons/%s/slots/%s', $weapon->getId(), $slot->getId());
        $this->client?->request('GET', $endpoint);

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Weapon/get_weapon_slot');
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
    public function getMaterial(): void
    {
        /** @var Weapon $weapon */
        $weapon = WeaponFactory::createOne()->object();
        if (null === $material = $weapon->getMaterials()->first() ?: null) {
            $material = WeaponMaterialFactory::createOne(['weapon' => $weapon]);
        }

        $endpoint = \sprintf('/api/weapons/%s/materials/%s', $weapon->getId(), $material->getId());
        $this->client?->request('GET', $endpoint);

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Weapon/get_weapon_material');
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
    public function getStatus(): void
    {
        /** @var Weapon $weapon */
        $weapon = WeaponFactory::createOne()->object();
        if (null === $status = $weapon->getStatuses()->first() ?: null) {
            $status = WeaponStatusFactory::createOne(['weapon' => $weapon]);
        }

        $endpoint = \sprintf('/api/weapons/%s/statuses/%s', $weapon->getId(), $status->getId());
        $this->client?->request('GET', $endpoint);

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Weapon/get_weapon_status');
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

    #[Test]
    public function getExtra(): void
    {
        /** @var Weapon $weapon */
        $weapon = WeaponFactory::createOne()->object();
        if (null === $extra = $weapon->getExtras()->first() ?: null) {
            $extra = WeaponExtraFactory::createOne(['weapon' => $weapon]);
        }

        $endpoint = \sprintf('/api/weapons/%s/extras/%s', $weapon->getId(), $extra->getId());
        $this->client?->request('GET', $endpoint);

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Weapon/get_weapon_extra');
    }
}
