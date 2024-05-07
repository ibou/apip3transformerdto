<?php

namespace App\Api\Contracts;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.api.transformer')]
interface ApiTransformer
{
    public function transform(object $source): object;

    public function supportsTransform(object $source, string $targetFqcn): bool;
}
