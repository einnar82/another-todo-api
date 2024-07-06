<?php

namespace Tests\Feature\API\TasksController;

class GetTaskTest extends AbstractTaskResourceTest
{
    public function testShowTaskSucceeds(): void
    {
        list($label, $task) = $this->createTestData();
        $response = $this->getJson(\sprintf('%s/%s', self::URI, $task->getKey()));

        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $task->getKey(),
            'title' => $task->title,
            'description' => $task->description,
            'labels' => [
                [
                    'id' => $label->getKey(),
                    'name' => $label->name,
                ]
            ]
        ]);
    }
}
