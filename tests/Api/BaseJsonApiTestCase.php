<?php

namespace App\Tests\Api;

use ApiTestCase\JsonApiTestCase;
use PHPUnit\Framework\Attributes\Before;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class BaseJsonApiTestCase extends JsonApiTestCase
{
    use ResetDatabase;
    use Factories;

    protected const int ITEMS_PER_PAGE = 30;

    #[Before]
    public function setUpClient(): void
    {
        $this->client = static::createClient([], ['HTTP_ACCEPT' => 'application/ld+json']);
    }
}
