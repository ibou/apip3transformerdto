<?php

namespace App\Tests\Api;

use App\Entity\Equipment\Companion\CompanionArmor;
use App\Entity\Equipment\Companion\CompanionWeapon;
use App\Factory\Equipment\Companion\CompanionArmorFactory;
use App\Factory\Equipment\Companion\CompanionWeaponFactory;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;

class CompanionTest extends BaseJsonApiTestCase
{
    #[Test]
    public function getWeapons(): void
    {
        CompanionWeaponFactory::createMany(self::ITEMS_PER_PAGE + 1);
        $this->client?->request('GET', '/api/companions/weapons');

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Companion/get_companion_weapons');
    }

    #[Test]
    public function getWeapon(): void
    {
        /** @var CompanionWeapon $weapon */
        $weapon = CompanionWeaponFactory::createOne()->object();
        $this->client?->request('GET', \sprintf('/api/companions/weapons/%s', $weapon->getId()));

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Companion/get_companion_weapon');
    }

    #[Test]
    public function getWeaponStatuses(): void
    {
        /** @var CompanionWeapon $weapon */
        $weapon = CompanionWeaponFactory::createOne()->object();
        $endpoint = \sprintf('/api/companions/weapons/%s/statuses', $weapon->getId());
        $this->client?->request('GET', $endpoint);

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Companion/get_companion_weapon_statuses');
    }

    #[Test]
    public function getArmors(): void
    {
        CompanionArmorFactory::createMany(self::ITEMS_PER_PAGE + 1);
        $this->client?->request('GET', '/api/companions/armors');

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Companion/get_companion_armors');
    }

    #[Test]
    public function getArmor(): void
    {
        /** @var CompanionArmor $armor */
        $armor = CompanionArmorFactory::createOne()->object();
        $this->client?->request('GET', \sprintf('/api/companions/armors/%s', $armor->getId()));

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Companion/get_companion_armor');
    }

    #[Test]
    public function getArmorResistances(): void
    {
        /** @var CompanionArmor $armor */
        $armor = CompanionArmorFactory::createOne()->object();
        $endpoint = \sprintf('/api/companions/armors/%s/resistances', $armor->getId());
        $this->client?->request('GET', $endpoint);

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Companion/get_companion_armor_resistances');
    }
}
