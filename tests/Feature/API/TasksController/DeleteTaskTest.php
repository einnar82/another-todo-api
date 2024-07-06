<?php

namespace Tests\Feature\API\TasksController;

use App\Models\Label;
use App\Models\Task;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteTaskTest extends TestCase
{
    use WithFaker;

    public function testDeleteSucceeds(): void
    {
        list(, $task) = $this->createTestData();

        $response = $this->deleteJson(\sprintf('%s/%s', self::URI, $task->getKey()));

        $response->assertNoContent();
        $this->assertDatabaseMissing('tasks', ['id' => $task->getKey()]);
    }
}
