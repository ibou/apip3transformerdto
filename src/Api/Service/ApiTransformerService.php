<?php

namespace App\Api\Service;

use App\Api\Contracts\ApiTransformer;
use App\Exception\ApiException;
use AutoMapper\AutoMapper;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class ApiTransformerService
{
    /** @var ApiTransformer[] */
    private iterable $transformers;

    /**
     * @param ApiTransformer[] $transformers
     */
    public function __construct(#[TaggedIterator('app.api.transformer')] iterable $transformers)
    {
        $this->transformers = $transformers;
    }

    public function transform(object $entity, string $targetFqcn): object
    {
        // 1. Looking for specific transformer
        foreach ($this->transformers as $transformer) {
            if ($transformer->supportsTransform($entity, $targetFqcn)) {
                return $transformer->transform($entity);
            }
        }

        // 2. If not, just transform using automapper
        $resource = AutoMapper::create()->map($entity, \get_class(new $targetFqcn()));
        if (null === $resource) {
            throw new ApiException('Failed to transform entity');
        }

        return $resource;
    }
}
