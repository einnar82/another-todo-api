<?php

namespace Tests;

use App\Models\Label;
use App\Models\Task;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use DatabaseMigrations;

    protected const URI = '/api/tasks';

    protected function createTestData(): array
    {
        /** @var Label $label */
        $label = Label::factory()->create();
        /** @var Task $task */
        $task = Task::factory()->create();
        $task->labels()->attach($label);

        return [$label, $task];
    }
}
