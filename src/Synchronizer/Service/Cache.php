<?php

namespace App\Synchronizer\Service;

use App\Entity\Equipment\Skill\Skill;
use App\Entity\Equipment\Skill\SkillLevel;
use App\Entity\Item;
use App\Entity\Monster\Monster;
use App\Entity\Quest\Client;
use App\Enum\Equipment\Skill\SkillType;
use App\Repository\Skill\SkillLevelRepository;
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

    /** @var array<string, Skill> */
    private array $_skills = [];

    /** @var array<string, SkillLevel> */
    private array $_skillsLevels = [];

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

    public function findSkill(string $name, SkillType $type): ?Skill
    {
        $key = \sprintf('%s_%s', $name, $type->value);
        if (isset($this->_skills[$key])) {
            return $this->_skills[$key];
        }

        $skill = $this->em->getRepository(Skill::class)->findOneBy(['name' => $name, 'type' => $type]);
        if (null === $skill) {
            return null;
        }

        $this->registerSkill($skill);

        return $skill;
    }

    private function registerSkill(Skill $skill): void
    {
        $key = \sprintf('%s_%s', $skill->getName(), $skill->getType()->value);
        $this->_skills[$key] = $skill;
    }

    public function findSkillLevel(string $name, SkillType $type, int $level): ?SkillLevel
    {
        $key = \sprintf('%s_%s_%s', $name, $type->value, $level);
        if (isset($this->_skillsLevels[$key])) {
            return $this->_skillsLevels[$key];
        }

        /** @var SkillLevelRepository $repository */
        $repository = $this->em->getRepository(SkillLevel::class);
        $skillLevel = $repository->findOneByNameTypeAndLevel($name, $type, $level);
        if (null === $skillLevel) {
            return null;
        }

        $this->registerSkillLevel($skillLevel);

        return $skillLevel;
    }

    private function registerSkillLevel(SkillLevel $level): void
    {
        $skill = $level->getSkill();
        if (null === $skill) {
            throw new \Exception('No skill link to level');
        }

        $key = \sprintf('%s_%s_%s', $skill->getName(), $skill->getType()->value, $level->getLevel());
        $this->_skillsLevels[$key] = $level;
    }

    public function clear(): void
    {
        $this->_items =
        $this->_clients =
        $this->_monsters =
        $this->_skills =
        $this->_skillsLevels = [];
    }
}
