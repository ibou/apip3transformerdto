<?php

namespace App\Api\Transformer\Quest;

use App\Api\Resource\Quest\QuestMonsterAttributeApi;
use App\Api\Transformer\AbstractTransformer;
use App\Entity\Quest\QuestMonsterAttribute;

/**
 * @method array<int, QuestMonsterAttributeApi> transformAll(iterable $entities)
 */
final class QuestMonsterAttributeApiTransformer extends AbstractTransformer
{
    public function transform(object $source): QuestMonsterAttributeApi
    {
        /** @var QuestMonsterAttribute $entity */
        $entity = $source;
        $resource = new QuestMonsterAttributeApi();

        $resource->id = $entity->getId();
        $resource->nbPlayers = $entity->getNbPlayers();
        $resource->hps = $entity->getHps();
        $resource->attack = $entity->getAttack();
        $resource->parts = $entity->getParts();
        $resource->defense = $entity->getDefense();
        $resource->ailment = $entity->getAilment();
        $resource->stun = $entity->getStun();
        $resource->stamina = $entity->getStamina();
        $resource->mount = $entity->getMount();

        // Mapping
        $resource->attribute = $entity;
        $resource->monster = $entity->getMonster();
        $resource->quest = $entity->getMonster()?->getQuest();

        return $resource;
    }

    public function supportsTransform(object $source): bool
    {
        return $source instanceof QuestMonsterAttribute;
    }
}
