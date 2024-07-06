<?php

namespace Tests\Feature\API\TasksController;

use App\Models\Label;
use App\Models\Task;
use Tests\TestCase;

class ListTaskTest extends TestCase
{
    public function testListSucceeds(): void
    {
        Task::factory()
            ->has(Label::factory()->count(3))
            ->count(10)
            ->create();

        $response = $this->getJson(self::URI);

        $response->assertOk();
        $response->assertJsonCount(10, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'labels' => [
                        '*' => [
                            'id',
                            'name',
                        ]
                    ]
                ]
            ]
        ]);
    }
}
