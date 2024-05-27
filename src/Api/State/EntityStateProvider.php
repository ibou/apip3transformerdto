<?php

namespace App\Api\State;

use ApiPlatform\Doctrine\Orm\Paginator;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\TraversablePaginator;
use App\Exception\ApiException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EntityStateProvider extends AbstractProvider
{
    /**
     * @param array<string, mixed>                                                   $uriVariables
     * @param array<string, mixed>|array{request?: Request, resource_class?: string} $context
     *
     * @throws ApiException
     * @throws \Exception
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object
    {
        if ($operation instanceof CollectionOperationInterface) {
            return $this->provideCollection($operation, $uriVariables, $context);
        }

        return $this->provideItem($operation, $uriVariables, $context);
    }

    /**
     * @param array<string, mixed>                                                   $uriVariables
     * @param array<string, mixed>|array{request?: Request, resource_class?: string} $context
     *
     * @throws \Exception
     */
    protected function provideCollection(Operation $operation, array $uriVariables = [], array $context = []): TraversablePaginator
    {
        $entities = $this->collectionProvider->provide($operation, $uriVariables, $context);
        \assert($entities instanceof Paginator);

        $resources = [];
        /** @var object $entity */
        foreach ($entities as $entity) {
            $resources[] = $this->transformerService->transform($entity);
        }

        return new TraversablePaginator(
            new \ArrayIterator($resources),
            $entities->getCurrentPage(),
            $entities->getItemsPerPage(),
            $entities->getTotalItems()
        );
    }

    /**
     * @param array<string, mixed>                                                   $uriVariables
     * @param array<string, mixed>|array{request?: Request, resource_class?: string} $context
     *
     * @throws ApiException
     * @throws \Exception
     */
    protected function provideItem(Operation $operation, array $uriVariables = [], array $context = []): object
    {
        $entity = $this->itemProvider->provide($operation, $uriVariables, $context);
        if (null === $entity) {
            throw new ApiException('Not found.', Response::HTTP_NOT_FOUND);
        }

        return $this->transformerService->transform($entity);
    }
}
