<?php

namespace App\Tests\Api;

use App\Entity\Skill\Skill;
use App\Factory\Skill\SkillFactory;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;

class SkillTest extends BaseJsonApiTestCase
{
    #[Test]
    public function getSkills(): void
    {
        SkillFactory::createMany(self::ITEMS_PER_PAGE + 1);
        $this->client?->request('GET', '/api/skills');

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Skill/get_skills');
    }

    #[Test]
    public function getSkill(): void
    {
        /** @var Skill $skill */
        $skill = SkillFactory::createOne()->object();
        $this->client?->request('GET', \sprintf('/api/skills/%s', $skill->getId()));

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Skill/get_skill');
    }

    #[Test]
    public function getSkillLevels(): void
    {
        /** @var Skill $skill */
        $skill = SkillFactory::createOne()->object();
        $endpoint = \sprintf('/api/skills/%s/levels', $skill->getId());
        $this->client?->request('GET', $endpoint);

        $response = $this->client?->getResponse() ?? new Response();
        $this->assertResponse($response, 'Skill/get_skill_levels');
    }
}
