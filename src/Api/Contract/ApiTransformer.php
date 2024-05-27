<?php

namespace App\Api\Contract;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('api.transformer')]
interface ApiTransformer
{
    public function transform(object $source): object;

    public function supportsTransform(object $source): bool;
}
