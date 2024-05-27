<?php

namespace App\Synchronizer\Service;

use App\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;

class Cache
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    /** @var array<string, array<string, Item|null>> */
    private array $_cache = [];

    public function findItem(string $name): ?Item
    {
        if (isset($this->_cache[Item::class][$name])) {
            return $this->_cache[Item::class][$name];
        }

        $item = $this->em->getRepository(Item::class)->findOneBy(['name' => $name]);
        $this->_cache[Item::class][$name] = $item;

        return $item;
    }

    public function clear(): void
    {
        $this->_cache = [];
    }
}
