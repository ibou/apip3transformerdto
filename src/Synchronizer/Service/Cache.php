<?php

namespace App\Synchronizer\Service;

use App\Entity\Item;
use App\Entity\Monster\Monster;
use App\Entity\Quest\Client;
use Doctrine\ORM\EntityManagerInterface;

class Cache
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    /** @var array<string, Item> */
    private array $_items = [];

    /** @var array<string, Client> */
    private array $_clients = [];

    /** @var array<string, Monster> */
    private array $_monsters = [];

    public function findItem(string $name): ?Item
    {
        if (isset($this->_items[$name])) {
            return $this->_items[$name];
        }

        $item = $this->em->getRepository(Item::class)->findOneBy(['name' => $name]);
        if (null === $item) {
            return null;
        }

        $this->registerItem($item);

        return $item;
    }

    private function registerItem(Item $item): void
    {
        $this->_items[$item->getName()] = $item;
    }

    public function findClient(string $name): ?Client
    {
        if (isset($this->_clients[$name])) {
            return $this->_clients[$name];
        }

        $client = $this->em->getRepository(Client::class)->findOneBy(['name' => $name]);
        if (null === $client) {
            return null;
        }

        $this->registerClient($client);

        return $client;
    }

    private function registerClient(Client $client): void
    {
        $this->_clients[$client->getName()] = $client;
    }

    public function findMonster(string $name): ?Monster
    {
        if (isset($this->_monsters[$name])) {
            return $this->_monsters[$name];
        }

        $monster = $this->em->getRepository(Monster::class)->findOneBy(['name' => $name]);
        if (null === $monster) {
            return null;
        }

        $this->registerMonster($monster);

        return $monster;
    }

    private function registerMonster(Monster $monster): void
    {
        $this->_monsters[$monster->getName()] = $monster;
    }

    public function clear(): void
    {
        $this->_items = $this->_clients = $this->_monsters = [];
    }
}
