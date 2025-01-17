<?php

namespace Tests\Feature\API\TasksController;

use Tests\TestCase;

class AbstractTaskResourceTest extends TestCase
{
    public function testTaskNotFound(): void
    {
        $response = $this->deleteJson(\sprintf('%s/%s', self::URI, 9999999999));

        $response->assertNotFound();
    }
}
