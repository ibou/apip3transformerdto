<?php

namespace App\Api\Transformer;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('api.transformer')]
abstract class AbstractTransformer
{
    abstract public function transform(object $source): object;

    abstract public function supportsTransform(object $source): bool;

    /**
     * @param list<object> $entities
     *
     * @return array<int, object>
     */
    protected function transformAll(iterable $entities): array
    {
        $resources = [];
        foreach ($entities as $_entity) {
            $resources[] = $this->transform($_entity);
        }

        return $resources;
    }
}
