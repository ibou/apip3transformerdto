<?php

namespace App\Api\Service;

use App\Api\Contract\ApiTransformer;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class ApiTransformerService
{
    /** @var ApiTransformer[] */
    private iterable $transformers;

    /**
     * @param ApiTransformer[] $transformers
     */
    public function __construct(#[TaggedIterator('api.transformer')] iterable $transformers)
    {
        $this->transformers = $transformers;
    }

    public function transform(object $entity): object
    {
        foreach ($this->transformers as $transformer) {
            if ($transformer->supportsTransform($entity)) {
                return $transformer->transform($entity);
            }
        }

        throw new \Exception('No transformer provide');
    }
}
