<?php

namespace Tests\Feature\API\TasksController;

use App\Models\Task;

class GetTaskTest extends AbstractTaskResourceTest
{
    public function testShowTaskSucceeds(): void
    {
        $task = Task::factory()->create();
        $response = $this->getJson(\sprintf('%s/%s', self::URI, $task->getKey()));

        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $task->getKey(),
            'title' => $task->title,
            'description' => $task->description,
            'labels' => $task->labels
        ]);
    }
}
