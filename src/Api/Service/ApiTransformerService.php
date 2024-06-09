<?php

namespace App\Api\Service;

use App\Api\Transformer\AbstractTransformer;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class ApiTransformerService
{
    /** @var AbstractTransformer[] */
    private iterable $transformers;

    /**
     * @param AbstractTransformer[] $transformers
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
