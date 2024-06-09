<?php

namespace App\Factory\Quest;

use App\Entity\Quest\Client;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<Client>
 */
final class ClientFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->jobTitle(),
        ];
    }

    protected static function getClass(): string
    {
        return Client::class;
    }
}
